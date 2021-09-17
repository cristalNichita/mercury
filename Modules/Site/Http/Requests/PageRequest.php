<?php

namespace Modules\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Site\Entities\Page;
use Modules\Site\Helper\SiteHelper;

class PageRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        if ($this->method == 'POST') {
            $this->merge([
                'slug' => SiteHelper::transformSlug($this->slug, $this->title, $this->type)
            ]);
        }

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required',
            'type' => 'required',
            'active' => 'boolean',
            'author' => 'required_if:type,'.Page::NEWS_TYPE.'|nullable|string',
            'category' => 'required_if:type,'.Page::PROJECT_TYPE.'|nullable|integer',
            'newMainImage' => 'nullable|image',
            'newGalleryImages' => 'array',
            'newGalleryImages.*' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Заполните заголовок',
            'content.required' => 'Заполните контент',
            'author.required_if' => 'Укажите автора',
            'category.integer' => 'Выберите категорию',
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
