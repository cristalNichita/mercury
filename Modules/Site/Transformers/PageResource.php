<?php

namespace Modules\Site\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $mainImage = $this->getMainImage();
        $gallery = $this->getGallery();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'type' => $this->type,
            'active' => $this->active,
            'image' => MediaResource::make($mainImage),
            'gallery' => MediaResource::collection($gallery),
        ];
    }
}
