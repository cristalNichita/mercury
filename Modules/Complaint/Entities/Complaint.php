<?php

namespace Modules\Complaint\Entities;

use App\Models\User;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Entities\Order;

class Complaint extends Model
{
    use HasFactory;
    use SortableTrait;

    protected $fillable = ['user_id','status_id','order_id', 'type_id', 'description', 'comment'];
    protected $appends = ['state', 'complaint_date'];

    const STATUS_ID_STOP = 1;
    const STATUS_ID_INJOB = 2;
    const STATUS_ID_REJECT = 3;
    const STATUS_ID_ACCEPT = 4;
    const STATUS_ID_DONE = 5;

    const STATUSES = [
        self::STATUS_ID_STOP    => 'Отменена подававшим',
        self::STATUS_ID_INJOB   => 'Принято к рассмотрению',
        self::STATUS_ID_REJECT  => 'Отклонена',
        self::STATUS_ID_ACCEPT  => 'Рекламация принята',
        self::STATUS_ID_DONE    => 'Исполнено'
    ];
    const STATUSES_ALIAS = [
        'create' => self::STATUS_ID_STOP,
        'in-job' => self::STATUS_ID_INJOB,
        'reject' => self::STATUS_ID_REJECT,
        'accept' => self::STATUS_ID_ACCEPT,
        'done'   => self::STATUS_ID_DONE
    ];

    const TYPES = [
        1 => 'Бракованный товар',
        2 => 'Возврат',
        3 => 'Надостача',
        4 => 'Ошибка в комплектации'
    ];

    /**
     * @return mixed
     */
    public function getStateAttribute()
    {
        return self::STATUSES[$this->attributes['status_id']] ?? null;
    }

    /**
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return self::TYPES[$this->type_id] ?? null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(ComplaintFile::class,'complaint_id');
    }

    public function getComplaintDateAttribute()
    {
        if (isset($this->created_at)) {
            return date('d.m.Y \в H:m', strtotime($this->created_at));
        }
    }

    /**
     * @return array
     */
    public static function getStatusNamedArr()
    {
        return [
            [
                'id'    => self::STATUS_ID_STOP,
                'name'  => self::STATUSES[self::STATUS_ID_STOP]
            ],
            [
                'id'    => self::STATUS_ID_INJOB,
                'name'  => self::STATUSES[self::STATUS_ID_INJOB]
            ],
            [
                'id'    => self::STATUS_ID_REJECT,
                'name'  => self::STATUSES[self::STATUS_ID_REJECT]
            ],
            [
                'id'    => self::STATUS_ID_ACCEPT,
                'name'  => self::STATUSES[self::STATUS_ID_ACCEPT]
            ],
            [
                'id'    => self::STATUS_ID_DONE,
                'name'  => self::STATUSES[self::STATUS_ID_DONE]
            ],
        ];
    }
}
