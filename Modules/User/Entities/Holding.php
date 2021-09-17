<?php

namespace Modules\User\Entities;

use App\Models\User;
use App\Traits\FilterableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;

/**
 * Class Holding
 * @package Modules\User\Entities
 *
 * @property-read Contact|null $owner Основное контактное лицо (создатель)
 */
class Holding extends Model
{
    use FilterableTrait;

    protected $fillable = ['id_1c', 'guid_site', 'name', 'contact_id'];


    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function orders()
    {
        $holding_id = $this->id;

        return Order::whereHas('contact', function ($query) use ($holding_id) {
            $query->where('holding_id', '=', $holding_id);
        })->orWhereHas('company', function ($query) use ($holding_id) {
            $query->where('holding_id', '=', $holding_id);
        });
    }

    /**
     * Основной контакт (создатель ходинга)
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function filterTitle($builder, $value)
    {
        $builder->where('name', 'like', "%{$value}%")
            ->orWhereHas('contacts', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%");
            })
            ->orWhereHas('companies', function ($query) use ($value) {
                $query->where('name', 'like', "%{$value}%")
                    ->orWhere('inn', 'like', "$value%");
            })
            ->orWhereHas('contacts.params', function ($query) use ($value) {
                $query->where('value', 'like', "%{$value}%");
            })
            ->orWhereHas('companies.params', function ($query) use ($value) {
                $query->where('value', 'like', "%{$value}%");
            })
            ->orderByRaw("case
                when `name` LIKE '{$value}' then 1
                when `name` LIKE '{$value}%'  then 2
                when `name` LIKE '%{$value}%'  then 3
                else 4 end");
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->guid_site = Str::uuid();
        });
    }

}
