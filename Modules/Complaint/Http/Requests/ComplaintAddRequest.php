<?php

namespace Modules\Complaint\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Complaint\Entities\Complaint;

class ComplaintAddRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id'      => ['required', Rule::exists('orders', 'id')->where('user_id', auth()->user()->id)],
            'type_id'       => ['required', Rule::in(array_keys(Complaint::TYPES))],
            'description'   => 'required|string',
            'comment'       => 'required|string',
            'files'         => 'nullable|array',
            'files.*'         => 'file|max:5120',
        ];
    }

    public function attributes()
    {
        return [
          'order_id' => 'Идентификатор Заказа',
          'type_id'  => 'Тип',
          'description' => 'Описание',
          'comment' => 'Комментарий',
          'files' => 'Файлы',
          'files.*' => 'Файл',
        ];
    }
}
