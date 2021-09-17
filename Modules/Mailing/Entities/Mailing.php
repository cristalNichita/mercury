<?php

namespace Modules\Mailing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mailing extends Model
{
    use HasFactory;

    const TYPES_USER = 0;
    const TYPES_ADMIN = 1;

    protected $casts = [
        'type' => 'boolean'
    ];

    protected $fillable = [
        'name',
        'event_id','status_id',
        'mail_template', 'type'
    ];

    public function event()
    {
        return $this->hasOne(MailingEvents::class, 'id', 'event_id');
    }

    public function status()
    {
        return $this->hasOne(MailingEventStatus::class, 'id', 'status_id');
    }

    public function getEventNameAttribute () {
        $event = $this->event()->first();
        return $event->name ?? 'без события';
    }

    public function getEventStatusAttribute () {
        $status = $this->status()->first();
        return $status->name ?? 'без статуса';
    }
}
