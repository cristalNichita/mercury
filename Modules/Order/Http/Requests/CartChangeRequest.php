<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartChangeRequest extends FormRequest
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
            'item_id' => 'required|exists:cart_items,id',
            'count' => 'required|integer'
        ];
    }
}
