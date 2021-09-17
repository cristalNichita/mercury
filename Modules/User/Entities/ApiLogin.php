<?php

namespace Modules\User\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ApiLogin extends Model
{
    use Notifiable;

    protected $fillable = ['type', 'phone', 'email', 'code'];

    protected $dates = ['created_at', 'updated_at'];


    public function isAwait(int $seconds = 0, $time = null)
    {
        return $time < $this->created_at->addSeconds($seconds);
    }

    public function awaitSeconds(int $seconds = 0, $time = null)
    {
        $end = $this->created_at->addSeconds($seconds);
        return $time->diffInSeconds($end);
    }

    public function getUser()
    {
        if ($this->type === 'phone') {
            return User::firstWhere('phone', $this->phone);
        }

        return User::firstWhere('email', $this->email);
    }

    public function routeNotificationForNutnetSms()
    {
        return $this->phone;
    }
}
