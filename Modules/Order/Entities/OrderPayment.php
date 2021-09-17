<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{

    protected $fillable = ['code', 'status', 'price', 'params'];

    protected $casts = ['params' => 'array'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
