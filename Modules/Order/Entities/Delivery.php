<?php

namespace Modules\Order\Entities;

use App\Traits\ImagesTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

class Delivery extends Model implements HasMedia
{
    use HasFactory;
    use SortableTrait;
    // Картинки
    use ImagesTrait;

    // Картинки нужны всегда, но не нужны в виде media
    protected $with = ['media'];

    protected $appends = ['image'];


    protected $fillable = ['name', 'code', 'active', 'sort', 'delivery_class'];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\DeliveryFactory::new();
    }

    public function scopeActive($query) {

        return $query->where('active', true);
    }

    public function setImage($file)
    {
        $this
            ->addMedia($file)
            ->toMediaCollection('image');
    }

    public function getServiceAttribute()
    {
        return new $this->delivery_class;
    }
}
