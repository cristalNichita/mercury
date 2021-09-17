<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartClearRequest extends FormRequest
{

    public function rules()
    {
        return [
            'cart_id' => 'required|uuid|exists:carts,id',
        ];
    }
}
