<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\Product;

/**
 * Class CartItem
 * @package Modules\Order\Entities
 * @property Product $product Товар
 * @property double $price Цена
 * @property int $count Кол-во
 * @property-read double $total_price Стоимость
 */
class CartItem extends Model
{

    protected $fillable = [
        'product_id',
        'name', 'article',
        'old_price', 'price',
        'count',
    ];
    protected $with = ['product.recommendation.recommendedProduct'];
    protected $appends = ['total_price'];
    protected $hidden = ['cart_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getTotalPriceAttribute()
    {
        return $this->price * $this->count;
    }

    /** @deprecated */
    public function canChangeQuantity($count)
    {
        if ($this->product) {
            $can = $this->product->quantity >= $count;
            $count = $this->product->quantity;
        } else {
            $can = false;
            $count = 0;
        }

        return [
            'can' => $can,
            'productQuantity' => $count
        ];
    }
}
