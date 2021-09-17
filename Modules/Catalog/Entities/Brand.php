<?php

namespace Modules\Catalog\Entities;

use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Catalog\Database\factories\BrandFactory;

class Brand extends Model
{
    use HasFactory, FilterableTrait;

    protected $fillable = ['title'];

    protected static function newFactory()
    {
        return BrandFactory::new();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
