<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Rules\CheckActiveUser;

/**
 * Class ApiLoginRequest
 * @package Modules\User\Http\Requests
 * @property string $password пароль
 * @property string $email Email
 * @property string $phone Телефон
 * @property string $type Тип входа
 */
class ApiLoginRequest extends FormRequest
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
            'password' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Поле телефона обязательное',
            'type.required' => 'Тип входа обязательный',
            'type.in' => 'Допустимые типы входа: phone email',
            'email.required_if' => 'Поле EMail обязательное',
            'email.exists' => 'Пользователь не существует',
            'email.exists.active' => 'Заблокирован',
            'email.email' => 'EMail имеет неправильный формат',
        ];
    }

}
