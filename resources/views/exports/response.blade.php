<table>
    <thead>
        <tr>
            @foreach ($fields as $field)
                <th>{{ $field->question }}</th>
            @endforeach
            <th>Date Submitted</th>
        </tr>
    </thead>
    <tbody>
        @foreach($responses as $response)
            <tr>
                @foreach ($fields as $field)
                    @php
                        $field_responses = $field->responses;
                        $field_response = $field_responses->where('form_response_id', $response->id)->first();
                    @endphp
                    <td>{!! ($field_response) ? $field_response->getAnswerForTemplate($field->template) : '' !!}</td>
                @endforeach
                <td>{{date( "Y-M-d H:i:s", strtotime( $response->created_at ) + 7 * 3600 )}}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>
