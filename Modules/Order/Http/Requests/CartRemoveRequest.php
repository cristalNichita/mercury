<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRemoveRequest extends FormRequest
{

    public function rules()
    {
        return [
            'cart_id' => 'required|uuid|exists:carts,id',
            'items' => 'required|array',
            'items.*' => 'required|exists:cart_items,id'
        ];
    }
}
