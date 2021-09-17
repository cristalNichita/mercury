<?php

namespace Modules\User\Http\Requests;

use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\UsedInUserRule;


class ApiUserContactRequest extends FormRequest
{

    public function rules()
    {
        return [
            'id' => 'nullable',
            'name' => 'required',
            'email' => ['required', 'email:rfc,dns', new UsedInUserRule()],
            'phone' => ['required', new UsedInUserRule()],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Обязательное поле',
            'phone.required' => 'Обязательное поле',
            'email.required' => 'Обязательное поле',
            'email.email' => 'Неверный формат',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->phone) {
            $this->merge(['phone' => Helper::clearPhone($this->phone),]);
        }
    }
}
