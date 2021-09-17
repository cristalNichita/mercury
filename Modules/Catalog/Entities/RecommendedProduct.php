<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecommendedProduct extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['product_id', 'recommended_product_id'];

    protected static function newFactory()
    {
        return \Modules\Catalog\Database\factories\RecommendedProductFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function recommendedProduct()
    {
        return $this->hasOne(Product::class, 'id', 'recommended_product_id');
    }

    public function scopeGoodsDay(Builder $query) {
        return $query->whereNull('recommended_product_id');
    }

    public function scopeWithoutGoodsDay(Builder $query) {
        return $query->whereNotNull('recommended_product_id');
    }
}
