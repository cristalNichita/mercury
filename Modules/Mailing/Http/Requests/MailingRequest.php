<?php

namespace Modules\Mailing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'mail_template' => 'required|string',
            //'event_id' => 'nullable|exists:mailing_events,id',
            //'status_id' => 'nullable|exists:mailing_event_statuses,id',
            'event_id' => 'nullable|integer',
            'status_id' => 'nullable|integer',
            'type' => 'boolean'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
