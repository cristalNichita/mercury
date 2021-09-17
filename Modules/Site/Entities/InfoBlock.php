<?php

namespace Modules\Site\Entities;

use App\Traits\ImagesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class InfoBlock extends Model implements HasMedia
{
    use HasFactory;

    // Картинки
    use ImagesTrait;

    protected function getFitMethod()
    {
        return Manipulations::FIT_CONTAIN;
    }

    // Картинки нужны всегда, но не нужны в виде media
    protected $with = ['media'];
    protected $hidden = ['media', 'updated_at', 'created_at'];

    protected $fillable = [
        'title', 'description',
        'background_color', 'slug',
        'in_main'
    ];

    protected $appends = ['image'];

    protected static function newFactory()
    {
        return \Modules\Site\Database\factories\InfoBlockFactory::new();
    }

    public function setImage($file)
    {
        try {
            $this
                ->addMedia($file)
                ->toMediaCollection('image');
        }
        catch (FileDoesNotExist $e) {}
        catch (FileIsTooBig $e) {}
    }

    public function getImage()
    {
        $media = $this->getMedia('image');
        return $media->count() > 0 ? $media[0] : null;
    }

    public function deleteImage()
    {
        $image = $this->getImage();

        if ($image)
        {
            $image->delete();
        }
    }

    public function page()
    {
        return $this->hasOne(Page::class, 'slug', 'slug')
                    ->where('type', '=', Page::INFO_BLOCKS);
    }

    public function scopeInMain(Builder $query)
    {
        return $query->where('in_main', '=', 1);
    }
}
