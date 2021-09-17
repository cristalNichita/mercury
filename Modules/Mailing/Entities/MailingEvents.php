<?php

namespace Modules\Mailing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * События россылки
 * @package Modules\Mailing\Entities
 */
class MailingEvents extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'handling'];

//    protected $casts = [
//        'handling' => 'array',
//    ];

    /**
     * Статусы события
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function statuses()
    {
        return $this->hasMany(MailingEventStatus::class, 'event_id');
    }

    /**
     * Рассылки события
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function mailings()
    {
        return $this->belongsToMany(Mailing::class);
    }

    public function getHandlingAttribute($value) {
        return json_decode($value, true);
    }
}
