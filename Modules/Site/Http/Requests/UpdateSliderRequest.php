<?php

namespace Modules\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'exclude_if:type,1|required|string|max:255',
            'description' => 'exclude_if:type,1|required|string|max:255',
            'button_text' => 'exclude_if:type,1|required|string|max:255',
            'button_color' => 'exclude_if:type,1|required|string|size:7',
            'url' => 'required|string|max:255',
            'active' => 'boolean',
            'new_image' => 'image|nullable',
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
