<?php

namespace Modules\Mailing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailingEventStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'event_id'];

    public function event()
    {
        return $this->belongsTo(MailingEvent::class);
    }
}
