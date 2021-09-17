<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'role' => 'required',
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email:rfc,dns',
            ],
            'phone' => [
                'required',
            ],
            'password' => 'required|same:password_confirmation|min:6',
            'password_confirmation' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'role.required' => 'Выберите роль',
            'phone.required' => 'Поле телефона обязательное',
            'email.required' => 'Поле EMail обязательное',
            'email.email' => 'EMail имеет неправильный формат',
            'password.required' => 'Поле пароль обязательно для заполнения',
            'password.same' => 'Поле пароль и подтверждение пароля должны совпадать',
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
