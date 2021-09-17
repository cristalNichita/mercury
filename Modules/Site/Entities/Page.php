<?php

namespace Modules\Site\Entities;

use App\Traits\FilterableTrait;
use App\Traits\ImagesTrait;
use App\Traits\SortableTrait;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Page extends Model implements HasMedia
{
    use HasFactory, Sluggable, FilterableTrait, SortableTrait;

    // Картинки
    use ImagesTrait;

    // Картинки нужны всегда, но не нужны в виде media
    protected $with = ['media'];
    protected $hidden = ['media', 'updated_at', 'created_at'];

    protected $fillable = [
        'title', 'slug', 'content', 'author', 'type', 'category', 'active', 'published_at'
    ];

    protected $appends = ['image', 'gallery'];

    const PROJECT_TYPE = 0;
    const SERVICE_TYPE = 1;
    const NEWS_TYPE = 2;
    const INFO_BLOCKS = 3;

    const types = [
        'projects' => self::PROJECT_TYPE,
        'services' => self::SERVICE_TYPE,
        'news' => self::NEWS_TYPE,
        'info' => self::INFO_BLOCKS,
    ];

    protected static function newFactory()
    {
        return \Modules\Site\Database\factories\PageFactory::new();
    }

    public function setActiveAttribute($value)
    {
        if ($value && !$this->published_at && $this->type == self::NEWS_TYPE)
        {
            $this->attributes['published_at'] = Carbon::now();
        }

        $this->attributes['active'] = $value;
    }

    // Методы для работы с главным изображением
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

    // Методы для работы с галлерей
    public function addImagesToGallery($files)
    {
        foreach ($files as $file)
        {
            $this->addToGallery($file);
        }
    }

    public function addToGallery($file)
    {
        try {
            $this
                ->addMedia($file)
                ->toMediaCollection('gallery');
        }
        catch (FileDoesNotExist $e) {}
        catch (FileIsTooBig $e) {}
    }

    public function getGallery()
    {
        return $this->getMedia('gallery');
    }

    // Scopes
    public function scopeProjects(Builder $query)
    {
        return $query->where('type', self::PROJECT_TYPE);
    }

    public function scopeServices(Builder $query)
    {
        return $query->where('type', self::SERVICE_TYPE);
    }

    public function scopeNews(Builder $query)
    {
        return $query->where('type', self::NEWS_TYPE);
    }

    public function scopeInfoPages(Builder $query)
    {
        return $query->where('type', self::INFO_BLOCKS);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', 1);
    }

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->orWhere('id', $value)->firstOrFail();
    }

    public function infoBlock()
    {
        return $this->hasOne(InfoBlock::class, 'slug', 'slug');
    }
}
