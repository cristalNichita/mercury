<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryCalculateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to_location' => 'required|array',
            'to_location.code' => 'required|integer',
            'to_location.city' => 'nullable|string|max:255',
            'to_location.address' => 'required|string',
            'cart_id' => 'required|uuid|exists:carts,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
