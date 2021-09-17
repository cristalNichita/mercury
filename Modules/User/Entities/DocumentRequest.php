<?php

namespace Modules\User\Entities;

use App\Models\User;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DocumentRequest
 * @package Modules\User\Entities
 * @deprecated ???
 */
class DocumentRequest extends Model
{
    use HasFactory;
    use SortableTrait;

    protected $fillable = ['user_id','status_id', 'type_id'];
    protected $appends = ['state', 'request_date'];

    const STATUS_ID_NEW = 1;
    const STATUS_ID_DONE = 2;
    const STATUS_ID_REJECT = 3;

    const STATUSES = [
        self::STATUS_ID_NEW    => 'Новая заявка',
        self::STATUS_ID_DONE   => 'Выполнено',
        self::STATUS_ID_REJECT  => 'Отклонена'
    ];
    const STATUSES_ALIAS = [
        'new' => self::STATUS_ID_NEW,
        'done' => self::STATUS_ID_DONE,
        'reject' => self::STATUS_ID_REJECT
    ];

    const TYPES = [
        1 => Document::DOC_TYPE_1_TITLE,
        2 => Document::DOC_TYPE_2_TITLE
    ];

    /**
     * @return mixed
     */
    public function getStateAttribute()
    {
        return self::STATUSES[$this->attributes['status_id']];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function getRequestDateAttribute()
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
                'id'    => self::STATUS_ID_NEW,
                'name'  => self::STATUSES[self::STATUS_ID_NEW]
            ],
            [
                'id'    => self::STATUS_ID_DONE,
                'name'  => self::STATUSES[self::STATUS_ID_DONE]
            ],
            [
                'id'    => self::STATUS_ID_REJECT,
                'name'  => self::STATUSES[self::STATUS_ID_REJECT]
            ]
        ];
    }

    public function create()
    {
        return $this->save();
    }
}
