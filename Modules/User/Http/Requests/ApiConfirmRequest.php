<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ApiConfirmRequest
 *
 * @package Modules\User\Http\Requests
 * @property string $email Email
 * @property string $phone Телефон
 * @property string $type Тип подтверждения
 * @property string $code Код
 */
class ApiConfirmRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Поле телефона обязательное',
            'type.required' => 'Тип подтверждения обязательный',
            'type.in' => 'Допустимые типы отправки кода: phone email',
            'email.required_if' => 'Поле EMail обязательное',
            'email.email' => 'EMail имеет неправильный формат',
            'code.digits' => 'Код может состоять только из цифр',
        ];
    }
}
