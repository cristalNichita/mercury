<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreHouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'active', 'sort', 'lat', 'long'];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\StoreHouseFactory::new();
    }

    public function scopeActive($query) {

        return $query->where('active', true);
    }
}
