<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ApiUserPasswordRequest extends FormRequest
{

    public function rules()
    {
        return ['password' => 'required|min:6'];
    }

    public function messages()
    {
        return [
            'password.required' => 'Обязательное поле',
            'password.min' => 'Минимум 6 символов',
        ];
    }

}
