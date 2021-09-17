<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'sort' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
            'newImage' => 'nullable|image|max:2048',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
