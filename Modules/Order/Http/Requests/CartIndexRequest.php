<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CartIndexRequest
 * @package Modules\Order\Http\Requests
 * @deprecated
 */
class CartIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cart_id' => 'nullable|uuid|exists:carts,id',
        ];
    }
}
