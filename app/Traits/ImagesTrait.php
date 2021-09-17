<?php


namespace App\Traits;


use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ImagesTrait
{
    use InteractsWithMedia;

    protected function getFitMethod()
    {
        return Manipulations::FIT_CROP;
    }

    protected function getSizes()
    {
        return [
            'small' => [122, 122],
            'small_2x' => [244, 244],

            'thumb' => [200, 200],
            'thumb_2x' => [400, 400],

            'big' => [600, 600],
            'big_2x' => [1200, 1200]
        ];
    }

    /**
     * Главная картинка
     * @return array
     */
    public function getImageAttribute()
    {

        $image = $this->getFirstMedia('image');
        if (!$image) {
            return null;
        }

        $result = ['id' => $image->id, 'name' => $image->file_name];
        foreach ($this->getSizes() as $conversion => $sizes) {
            $result[$conversion] = $this->getFirstMediaUrl('image', $conversion);
        }

        return $result;
    }

    /**
     * Список картинок галлереи
     * @return array
     */
    public function getGalleryAttribute()
    {
        $images = $this->getMedia('gallery')->map(function (Media $item) {
            $image_sizes = ['id' => $item->id];
            foreach ($this->getSizes() as $conversion => $sizes) {
                try {
                    $image_sizes[$conversion] = $item->getUrl($conversion);
                } catch (\Exception $exception) {
                    $image_sizes[$conversion] = null;
                }
            }
            return $image_sizes;
        })->toArray();
        return $images;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();

        $this->addMediaCollection('gallery');

        foreach ($this->getSizes() as $conversion => $sizes) {
            $this->addMediaConversion($conversion)
                ->fit($this->getFitMethod(), ...$sizes)
                ->performOnCollections('image');

            $this->addMediaConversion($conversion)
                ->fit($this->getFitMethod(), ...$sizes)
                ->performOnCollections('gallery');
        }
    }


    public function addOrUpdateImage($image)
    {
        $basename = basename($image);
        $find = $this->getMedia('gallery', function ($item) use ($basename) {
            return ($item->file_name == $basename);
        })->first();

        if ($find) {
            // размер разный - обновляем
            if ($find->size !== filesize($image)) {
                $this->deleteMedia($find);
                $this->copyMedia($image)->toMediaCollection('gallery');
                return true;
            }
        } else {
            $this->copyMedia($image)->toMediaCollection('gallery');
            return true;
        }

        return false;
    }
}
