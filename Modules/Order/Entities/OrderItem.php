<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;

class OrderItem extends Model
{

    protected $fillable = [
        'order_id', 'id_1c',
        'status', 'name',
        'old_price', 'price',
        'count', 'total',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
