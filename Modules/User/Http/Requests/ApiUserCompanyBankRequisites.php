<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiUserCompanyBankRequisites extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bank_name' => 'required',
            'bik' => 'required|digits:9',
            'invoice' => 'required|digits:20',
            'kor' => 'required|digits:20'

        ];
    }

    public function messages()
    {
        return [
            'bik.required' => 'Обязательное поле',
            'bik.digits' => 'Поле должно быть числовым (9 знаков)',
            'invoice.required' => 'Обязательное поле',
            'invoice.regex' => 'Поле должно быть числовым (20 знаков)',
            'kor.required' => 'Обязательное поле',
            'kor.regex' => 'Поле должно быть числовым (20 знаков)',
            'bank_name.required' => 'Обязательное поле'
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
