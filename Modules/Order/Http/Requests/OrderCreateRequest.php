<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id' => 'required|uuid|exists:carts,id',

            // когда будут точные данные по доставке
            //'tariff_code' => 'required|integer',
            //'city_code' => 'required|integer',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'required|string',
            'is_online_pay' => 'required_unless:need_invoice,true|boolean',
            'need_call' => 'required|boolean',
            'need_invoice' => 'required_unless:is_online_pay,true|boolean',
        ];
    }
}
