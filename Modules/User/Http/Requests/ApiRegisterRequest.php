<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ApiRegisterRequest
 * @package Modules\User\Http\Requests
 * @property string $password пароль
 * @property string $email Email
 * @property string $phone Телефон
 * @property string $type Тип регистрации
 */
class ApiRegisterRequest extends FormRequest
{

    public function rules()
    {
        return [
            'phone' => [
                'nullable',
                'required_if:type,==,phone',
            ],
            'type' => ['required', Rule::in(['phone', 'email'])],
            'email' => [
                'nullable',
                'required_if:type,==,email',
                'email:rfc,dns',
            ],
            'code' => 'nullable|digits:6',
            'password' => [
                'nullable',
                'required_if:type,==,email',
                'confirmed',
                'string',
                'min:6',
            ],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Поле телефона обязательное',
            'type.required' => 'Тип регистрации обязательный',
            'type.in' => 'Допустимые типы регистрации: phone, email',
            'email.required_if' => 'Поле EMail обязательное',
            'email.email' => 'EMail имеет неправильный формат',
            'password.required_if' => 'Поле пароль обязательное',
            'password.confirmed' => 'Пароли должны совпадать',
            'code.digits' => 'Код может состоять только из цифр',
        ];
    }
}
