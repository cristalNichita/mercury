<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalogProduct extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Catalog\Database\factories\AnalogProductFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function analog()
    {
        return $this->belongsTo(Product::class, 'id', 'analog_product_id');
    }
}
