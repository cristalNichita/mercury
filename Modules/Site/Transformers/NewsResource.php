<?php

namespace Modules\Site\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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

            'author' => $this->author,
            'published_at' => $this->published_at ? date('d.m.Y H:i', strtotime($this->published_at)) : null,
        ];
    }
}
