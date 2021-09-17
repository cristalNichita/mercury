<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cart_id' => 'nullable|uuid|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'count' => 'nullable|integer'
        ];
    }
}
