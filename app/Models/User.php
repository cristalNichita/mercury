<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\ImagesTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Modules\Integration\Entities\Document;
use Modules\Order\Entities\Cart;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Entities\Holding;
use Spatie\MediaLibrary\HasMedia;

/**
 * Class Product
 * @property string $name Имя (ФИО)
 * @property string $email Email пользователя
 * @property int $phone Телефон пользователя
 * @property int $role Роль пользователя
 * @property int $contact_id ID связанного контакта
 * @property-read Collection $categories Категории продукта
 * @property-read Collection $parameters_values Значения параметров
 * @package Modules\Catalog\Entities
 */
class User extends Authenticatable implements HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use ImagesTrait;
    use SortableTrait;

    public const ROLES = [
        'admin' => 1,
        'manager' => 2,
        'content' => 3,
        'regular_user' => 4,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone',
        'password', 'role', 'contact_id',

        /** @deprecated fields */
        'lastname', 'middlename', 'current_team_id',
        'inn', 'ogrn', 'kpp', 'bik',
        'position', 'position_title',
        'partner', 'type', 'guid','active', 'deleted', 'sms_code',
    ];

    protected $attributes = [
        'role' => self::ROLES['regular_user'],

        /** @deprecated fields */
        'current_team_id' => 4,
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    /**
     * Для Middleware проверка роли
     * @param string $roles
     * @return bool
     */
    public function hasRole($roles = []): bool
    {
        if (empty($roles)) {
            return true;
        }

        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }

        $role_slug = array_search($this->role, self::ROLES);

        return $role_slug && in_array($role_slug, $roles);
    }

    public function routeNotificationForNutnetSms()
    {
        return $this->phone;
    }

    public function setPhoneAttribute($phone)
    {
        $phone = Helper::clearPhone($phone);
        $this->attributes['phone'] = $phone;
    }

    /**
     * Связь с компанией
     * @return BelongsToMany
     *
     * @deprecated эта связь уже не используется.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('can_edit');
    }

    /**
     * Корзины пользователя
     * @return HasOne
     * @deprecated
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * @return BelongsToMany
     * @deprecated
     */
    public function holding_companies()
    {
        return $this->belongsToMany(Holding::class, 'holding_companies_users');
    }

    /**
     * @deprecated ???
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->password)) {
                $model->password = Hash::make(Str::random(10));
            }
        });
    }

    /**
     * Поиск по параметрам email
     * @param string $email
     * @return mixed
     *
     */
    public static function findByEmail(string $email)
    {
        if (empty($email)) {
            return null;
        }

        $user = self::firstWhere('email', $email);

        return $user ?: self::findByParam('email', $email);
    }

    /**
     * Поиск по параметрам phone
     * @param string $phone
     * @return mixed
     *
     */
    public static function findByPhone(string $phone)
    {
        $valid_phone = Helper::clearPhone($phone);

        if (empty($valid_phone)) {
            return null;
        }

        $user = self::firstWhere('phone', $valid_phone);

        return $user ?: self::findByParam('phone', $valid_phone);
    }

    public static function findByParam(string $type, string $value)
    {
        if (empty($value)) {
            return null;
        }
        return self::whereHas('contact.params', function ($subQuery) use ($type, $value) {
            return $subQuery->where(['type' => $type, 'value' => $value]);
        })->first();
    }

}
