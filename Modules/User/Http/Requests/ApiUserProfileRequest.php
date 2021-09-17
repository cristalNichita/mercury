<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ApiUserProfileRequest extends FormRequest
{

    public function rules()
    {
        return [
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Обязательное поле',
            'name.required' => 'Обязательное поле',
            'email.required' => 'Обязательное поле',
            'email.email' => 'Неверный формат',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
