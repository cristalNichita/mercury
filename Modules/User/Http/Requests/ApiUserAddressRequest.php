<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ApiUserAddressRequest extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'nullable',
            'view' => 'required',
            'value' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'view.required' => 'Обязательное поле',
            'value.required' => 'Обязательное поле',
        ];
    }
}
