<?php

namespace Modules\Site\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class InfoBlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => MediaResource::make($this->getImage()),
            'background_color' => $this->background_color,
            'in_main' => $this->in_main,
        ];
    }
}
