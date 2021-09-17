<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Rules\CheckActiveUser;

class ApiRegisterRequestOld extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'unique:users,email',
                'email:rfc,dns',
            ],
            'password' => [
                'min:6',
                'confirmed',
                'required'
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => 'Пароль обязательный',
            'email.required' => 'E-Mail обязательный',
            'email.unique' => 'Пользователь уже существует',
            'email.email' => 'E-Mail имеет неправильный формат',
            'password.confirmed' => 'Пароль и повтор пароля не совпадают',
            'password.min' => 'Пароль должен состоять из 6 символов',
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
