<?php

namespace App\Http\Controllers\Form;

use App\Exports\FormResponseExport;
use App\FieldResponse;
use App\Form;
use App\FormResponse;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ResponseController extends Controller
{
    public function index(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        $valid_request_queries = ['summary', 'individual'];
        $query = strtolower(request()->query('type', 'summary'));

        abort_if(!in_array($query, $valid_request_queries), 404);

        if ($query === 'summary') {
            $responses = [];
            $form->load('fields.responses', 'collaborationUsers', 'availability');
        } else {
            $form->load('collaborationUsers');

            $responses = $form->responses()->has('fieldResponses')->with('fieldResponses.formField')->paginate(1, ['*'], 'response');
        }

        return view('forms.response.index', compact('form', 'query', 'responses'));
    }

    public function store(Request $request, $form)
    {
        if ($request->ajax()) {
            $form = Form::where('code', $form)->first();

            if (!$form || $form->status !== Form::STATUS_OPEN) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_allowed',
                    'error' => (!$form) ? 'Form tidak valid' : 'Form tidak dapat diakses',
                ]);
            }

            $form_fields = $form->fields()->filled()->select(['id', 'attribute', 'required'])->get();
            $inputs = [];
            $validation_rules = [];
            $validation_messages = [];

            foreach ($form_fields as $field) {
                $attribute = str_replace('.', '_', $field->attribute);
                $input_data = [
                    'question' => $field->question,
                    'value' => array_get($request->all(), $attribute),
                    'required' => $field->required,
                    'options' => $field->options,
                    'template' => str_replace('-', '_', $field->template),
                ];

                $inputs[$attribute] = $input_data;
            }

            foreach ($inputs as $attribute => $input) {
                $rule = ($input['required']) ? 'required|' : 'nullable|';
                $messages = ($input['required']) ? ['required' => 'Seluruh pertanyaan dengan * wajib diisi'] : [];

                switch ($input['template']) {
                    case 'short_answer':
                        $rule .= 'string|min:3|max:255';
                        $messages['min'] = "Jawaban dari:  \"{$input['question']}\" harus lebih dari 3 karakter!";
                        $messages['max'] = "Jawaban dari: \"{$input['question']}\" harus kurang dari 255 karakter!";
                        break;
                    case 'long_answer':
                        $rule .= 'string|min_words:3|max:60000';
                        $message['min_words'] = "Jawaban dari: \"{$input['question']}\" harus lebih dari 3 karakter!";
                        $message['max'] = "Jawaban dari: \"{$input['question']}\" harus kurang dari :max karakter!";
                        break;
                    case 'checkboxes':
                        //For check box array
                        $validation_rules[$attribute] = "{$rule}max:" . count($input['options']);
                        $checkbox_message = ['max' => "Opsi yang dipilih untuk: \"{$input['question']}\" tidak valid"];
                        $validation_messages[$attribute] = array_merge($messages, $checkbox_message);

                        //For individual value
                        $rule .= 'string|in:' . implode(',', $input['options']);
                        $messages['in'] = "Opsi yang dipilih untuk: \"{$input['question']}\" tidak valid";
                        break;
                    case 'multiple_choices':
                    case 'drop_down':
                        $rule .= 'string|in:' . implode(',', $input['options']);
                        $messages['in'] = "Opsi yang dipilih untuk: \"{$input['question']}\" tidak valid";
                        break;
                    case 'date':
                        $rule .= 'string|date';
                        $messages['date'] = "Jawaban dari: \"{$input['question']}\" tidak valid";
                        break;
                    case 'time':
                        $rule .= 'string|date_format:H:i';
                        $messages['date_format'] = "Jawaban dari: \"{$input['question']}\" tidak valid";
                        break;
                    case 'linear_scale':
                        $rule .= "integer|between:{$input['options']['min']['value']},{$input['options']['max']['value']}";
                        $messages['between'] = "Jawaban dari: \"{$input['question']}\" tidak valid";
                        break;
                }

                $new_attribute = ($input['template'] === 'checkboxes') ? "{$attribute}.*" : $attribute;
                $validation_rules[$new_attribute] = $rule;
                $validation_messages[$new_attribute] = $messages;
            }

            $validator = \Validator::make($request->except('_token'), $validation_rules, array_dot($validation_messages));

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten();
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => $errors->first(),
                ]);
            }

            $response = new FormResponse([
                'respondent_ip' => (string) $request->ip(),
                'respondent_user_agent' => (string) $request->header('user-agent'),
            ]);

            $response->generateResponseCode();
            $form->responses()->save($response);

            foreach ($form_fields as $field) {
                $attribute = str_replace('.', '_', $field->attribute);
                $value = $request->input($attribute);

                $field_response = new FieldResponse([
                    'form_response_id' => $response->id,
                    'answer' => is_array($value) ? json_encode($value) : $value,
                ]);

                $field->responses()->save($field_response);
            }

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function destroy(Form $form, FormResponse $response)
    {
        $current_user = Auth::user();
        $user_not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        $not_allowed = ($user_not_allowed || $form->id !== $response->form_id);
        abort_if($not_allowed, 403);

        $response->delete();

        session()->flash('index', [
            'status' => 'success', 'message' => 'Respon berhasil di hapus.',
        ]);

        return redirect()->route('forms.responses.index', [$form->code, 'type' => 'individual']);
    }

    public function destroyAll(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 403);

        $responses = $form->responses()->get();
        abort_if(!$form->has('responses')->exists(), 403);

        $form->responses()->chunk(100, function ($responses) {
            foreach ($responses as $response) {
                $response->delete();
            }
        });

        session()->flash('index', [
            'status' => 'success', 'message' => 'Seluruh respon berhasil di hapus.',
        ]);

        return redirect()->route('forms.responses.index', $form->code);
    }

    public function eksporExcel(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        $not_allowed = $form->responses()->doesntExist();
        abort_if($not_allowed, 404);

        $filename = str_slug($form->title) . '.xlsx';
        return Excel::download(new FormResponseExport($form), $filename);
    }
}
