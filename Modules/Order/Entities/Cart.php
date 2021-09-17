<?php

namespace Modules\Order\Entities;

use App\Models\User;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Cart extends Model
{
    use Notifiable;
    use UsesUuid;

    protected $fillable = ['user_id', 'params'];

    protected $with = ['items'];
    protected $casts = ['params' => 'array'];

    protected $appends = ['total_price', 'total', 'recommended'];
    protected $hidden = ['created_at', 'updated_at', 'params', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /** @deprecated  */
    public function findItem(int $itemId)
    {
        return $this->items->find($itemId);
    }

    public function isOwner()
    {
        $user = auth()->user();
        return $user && $this->user_id === $user->id;
    }

    public function getTotalPriceAttribute()
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->total_price;
        }
        return $result;
    }

    public function getTotalAttribute()
    {
        $result = 0;
        foreach ($this->items as $item) {
            $result += $item->count;
        }
        return $result;
    }

    public function getRecommendedAttribute()
    {
        $random = 12;

        $recommended = collect([]);

        foreach ($this->items as $item) {
            $recommended = $recommended->merge(
                $item->product->recommendation
                    ->pluck('recommendedProduct')
            );
        }

        if ($recommended->count() > $random) {
            return $recommended->random($random);
        }

        return $recommended;
    }

    public function routeNotificationForMail($notification)
    {
        $user_email = $this->user()->first()->email;
        $contact_email = $this->user()->first()
                                ->contact()->first()->email;
        // Вернуть только адрес электронной почты ...
        return $contact_email ?? $user_email ;

        // Вернуть адрес электронной почты и имя ...
        //return [$this->email_address => $this->name];
    }
}
