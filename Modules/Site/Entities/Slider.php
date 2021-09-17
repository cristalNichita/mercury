<?php

namespace Modules\Site\Entities;

use App\Traits\ImagesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model implements HasMedia
{
    use HasFactory;

    // Картинки
    use ImagesTrait;

    protected function getSizes()
    {
        return [
            'small' => [375, 160],
            'small_2x' => [750, 320],

            'thumb' => [688, 357],
            'thumb_2x' => [1376, 714],

            'big' => [825, 357],
            'big_2x' => [1650, 714]
        ];
    }

    // Картинки нужны всегда, но не нужны в виде media
    protected $with = ['media'];
    protected $hidden = ['media', 'updated_at', 'created_at'];

    protected $fillable = [
        'title', 'description',
        'button_text', 'button_color', 'url',
        'active', 'type'
    ];

    protected $appends = ['image'];

    const MAIN = 0;
    const ABOUT_COMPANY = 1;

    const types = [
        'main' => self::MAIN,
        'about_company' => self::ABOUT_COMPANY,
    ];

    public function setImage($file)
    {
        $this
            ->addMedia($file)
            ->toMediaCollection('image');
    }

    protected static function newFactory()
    {
        return \Modules\Site\Database\factories\SliderFactory::new();
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', true);
    }

    public function scopeMain(Builder $query) {
        return $query->where('type', self::MAIN);
    }

    public function scopeAboutCompany(Builder $query) {
        return $query->where('type', self::ABOUT_COMPANY);
    }
}
