<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiUserCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'inn' => 'required|digits_between:10,12',
            'kpp' => 'required|digits:9',
            'ogrn' => 'required|digits_between:13,15',
            'email' => 'nullable|email:rfc,dns',
            'phone' => 'nullable|digits_between:6,12',
            'u_address' => 'nullable',
            'f_address' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'inn.required' => 'Обязательное поле',
            'inn.digits_between' => 'Поле должно быть числовым (10 или 12 знаков)',
            'kpp.required' => 'Обязательное поле',
            'kpp.digits_between' => 'Поле должно быть числовым (9 знаков)',
            'ogrn.required' => 'Обязательное поле',
            'ogrn.digits_between' => 'Поле должно быть числовым (13 или 15 знаков)',
            'name.required' => 'Обязательное поле',
            'email.email' => 'Неверный формат',
            'phone.digits_between' => 'Неверный формат'
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
