<?php

namespace App\Http\Controllers\Form;

use App\Form;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;

class FormController extends Controller
{
    public function index()
    {
        $current_user = Auth::user();

        $forms = $current_user->forms()->orWhereHas('collaborationUsers', function ($query) use ($current_user) {
            $query->where('form_collaborators.user_id', $current_user->id);
        })->latest()->get();

        return view('forms.form.index', compact('forms', 'current_user'));
    }

    public function create()
    {
        $current_user = Auth::user();

        $max_no_user_unclosed_forms = config('custom.forms.max_no_user_unclosed_forms');
        $unclosed_forms_count = $current_user->forms()
            ->whereIn('status', [Form::STATUS_DRAFT, Form::STATUS_PENDING, Form::STATUS_OPEN])
            ->count();

        if ($unclosed_forms_count == $max_no_user_unclosed_forms) {
            session()->flash('index', [
                'status' => 'warning', 'message' => "Anda memiliki {$max_no_user_unclosed_forms} form yang masih terbuka.",
            ]);

            return redirect()->route('forms.index');
        }

        return view('forms.form.create');
    }

    public function store(Request $request)
    {
        $current_user = Auth::user();

        $max_no_user_unclosed_forms = config('custom.forms.max_no_user_unclosed_forms');
        $unclosed_forms_count = $current_user->forms()
            ->whereIn('status', [Form::STATUS_DRAFT, Form::STATUS_PENDING, Form::STATUS_OPEN])
            ->count();

        $not_allowed = ($unclosed_forms_count == $max_no_user_unclosed_forms);
        abort_if($not_allowed, 403, "Anda memiliki {$max_no_user_unclosed_forms} form yang masih terbuka.");

        $this->validate($request, [
            'title' => 'required|string|min:3|max:190',
            'description' => 'required|string|min_words:3|max:30000',
        ]);

        $form = new Form([
            'title' => ucfirst($request->title),
            'description' => ucfirst($request->description),
            'status' => Form::STATUS_DRAFT,
        ]);

        $form->generateCode();
        $current_user->forms()->save($form);

        return redirect()->route('forms.show', $form->code);
    }

    public function show(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        $form->load('fields', 'collaborationUsers', 'availability');

        return view('forms.form.show', compact('form'));
    }

    public function edit(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        return view('forms.form.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        $this->validate($request, [
            'title' => 'required|string|min:3|max:190',
            'description' => 'required|string|min_words:3|max:30000',
        ]);

        $form->title = $request->title;
        $form->description = $request->description;
        $form->save();

        return redirect()->route('forms.show', $form->code);
    }

    public function draftForm(Request $request, $form)
    {
        if ($request->ajax()) {
            $form = Form::where('code', $form)->with('fields')->first();

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => 'Form tidak valid',
                ]);
            }

            $current_user = Auth::user();
            $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
            if ($not_allowed) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_allowed',
                    'error' => 'Form tidak valid',
                ]);
            }

            $inputs = [];
            $is_invalid_request = false;

            foreach ($request->except('_token') as $key => $value) {
                $key_parts = explode('_', $key);

                if (!is_array($key_parts) || count($key_parts) < 3) {
                    $is_invalid_request = true;
                    break;
                }

                $key_parts = array_reverse($key_parts);
                $field = array_shift($key_parts);
                $unique_key = array_shift($key_parts);
                $template = implode('_', array_reverse($key_parts));

                if (!in_array(str_replace('_', '-', $template), get_form_templates()->pluck('alias')->all())) {
                    $is_invalid_request = true;
                    break;
                }

                if ($template === 'linear_scale') {
                    $sub_key = substr($field, 0, 3);
                    if (in_array($sub_key, ['min', 'max'])) {
                        $field = "options.{$sub_key}." . substr($field, 3);
                    }
                }

                $new_key = "{$template}.{$unique_key}.{$field}";

                $inputs = array_add($inputs, $new_key, $value);
            }

            if ($is_invalid_request) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => 'Permintaan tidak valid. Tolong refresh halaman.',
                ]);
            }

            $validator = Validator::make($inputs, [
                'short_answer.*.question' => 'sometimes|required|string|min:3|max:255',
                'long_answer.*.question' => 'sometimes|required|string|min:3|max:60000',
                'multiple_choices.*.question' => 'sometimes|required|string|min:3|max:255',
                'multiple_choices.*.options.*' => 'required_with:multiple_choices.*.question|string|min:3|max:255',
                'checkboxes.*.question' => 'sometimes|required|string|min:3|max:255',
                'checkboxes.*.options.*' => 'required_with:checkboxes.*.question|string|min:3|max:255',
                'drop_down.*.question' => 'sometimes|required|string|min:3|max:255',
                'drop_down.*.options.*' => 'required_with:drop_down.*.question|string|min:3|max:255',
                'linear_scale.*.question' => 'sometimes|required|string|min:3|max:255',
                'linear_scale.*.options.min.value' => 'required_with:linear_scale.*.question|integer|in:0,1',
                'linear_scale.*.options.min.label' => 'nullable|string|min:3|max:255',
                'linear_scale.*.options.max.value' => 'required_with:linear_scale.*.question|integer|in:' . implode(',', range(2, 10)),
                'linear_scale.*.options.max.label' => 'nullable|string|min:3|max:255',
                'date.*.question' => 'sometimes|required|string|min:3|max:255',
                'time.*.question' => 'sometimes|required|string|min:3|max:255',
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten();
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => $errors->first(),
                ]);
            }

            foreach ($form->fields as $field) {
                $field->question = ucfirst(data_get($inputs, "{$field->attribute}.question"));
                $field->required = data_get($inputs, "{$field->attribute}.required") ? true : false;
                $field->options = data_get($inputs, "{$field->attribute}.options");
                $field->filled = true;
                $field->save();
            }

            ($form->status === Form::STATUS_DRAFT) and $form->status = Form::STATUS_PENDING;
            $form->save();

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function previewForm(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 404);

        return view('forms.form.view_form', ['form' => $form, 'view_type' => 'preview']);
    }

    public function openFormForResponse(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 403);

        $not_allowed = (!in_array($form->status, [Form::STATUS_PENDING, Form::STATUS_CLOSED]));
        abort_if($not_allowed, 403);

        $form->status = Form::STATUS_OPEN;
        $form->save();

        session()->flash('show', [
            'status' => 'success',
            'message' => 'Form anda berhasil dibuka untuk respon.',
        ]);

        return redirect()->route('forms.show', $form->code);
    }

    public function closeFormToResponse(Form $form)
    {
        $current_user = Auth::user();
        $not_allowed = ($form->user_id !== $current_user->id && !$current_user->isFormCollaborator($form->id));
        abort_if($not_allowed, 403);

        $not_allowed = ($form->status !== Form::STATUS_OPEN);
        abort_if($not_allowed, 403);

        $form->status = Form::STATUS_CLOSED;
        $form->save();

        session()->flash('show', [
            'status' => 'success',
            'message' => 'Form anda berhasil ditutup untuk respon.',
        ]);

        return redirect()->route('forms.show', $form->code);
    }

    public function viewForm(Form $form)
    {
        $not_allowed = !in_array($form->status, [Form::STATUS_OPEN, Form::STATUS_CLOSED]);
        abort_if($not_allowed, 404);

        return view('forms.form.view_form', ['form' => $form, 'view_type' => 'form']);
    }

    public function destroy($form)
    {
        if (request()->ajax()) {
            $form = Form::where('code', $form)->first();

            if (!$form || $form->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_found',
                    'error' => 'Form is invalid',
                ]);
            }

            if ($form->status === Form::STATUS_OPEN) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_allowed',
                    'error' => 'Form harus ditutup sebelum di hapus.',
                ]);
            }

            $form->delete();
            return response()->json([
                'success' => true,
            ]);
        }

        $form = Form::where('code', $form)->firstOrFail();

        $not_allowed = ($form->user_id !== Auth::id());
        abort_if($not_allowed, 404);

        $not_allowed = ($form->status === Form::STATUS_OPEN);
        abort_if($not_allowed, 403);

        $form->delete();

        session()->flash('index', [
            'status' => 'success',
            'message' => 'Form berhasil di hapus',
        ]);

        return redirect()->route('forms.index');
    }
}
