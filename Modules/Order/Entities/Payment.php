<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Modules\Order\Traits\UnitellerTrait;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const
        STATUS_WAITING = 'waiting',
        STATUS_SUCCESS = 'success',
        STATUS_FAIL = 'fail';

    public static $status_names = [
        self::STATUS_WAITING => ['Ожидание оплаты', 'info'],
        self::STATUS_SUCCESS => ['Оплачено успешно', 'success'],
        self::STATUS_FAIL => ['Не оплачено', 'danger'],
    ];

    protected $fillable = ['title', 'amount', 'status', 'info', 'user_id', 'order_id'];

    protected $appends = [
        'status_name'
    ];

    public function getStatusNameAttribute()
    {
        return isset(self::$status_names[$this->status])
            ? self::$status_names[$this->status][0]
            : $this->status;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
