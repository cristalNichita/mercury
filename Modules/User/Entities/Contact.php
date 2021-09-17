<?php

namespace Modules\User\Entities;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\User\Traits\ParamsTrait;

/**
 * Class Contact
 * @property int $id
 * @property string $guid
 * @property string $guid_site
 *
 * @property string $name ФИО
 * @property string $position Должность
 *
 * @property string $phone Телефон (мутатор)
 * @property string $email Email (мутатор)
 * @property string $address Адрес первый из списка (мутатор)
 *
 * @property-read User $user
 * @property-read Holding holding
 * @property-read Collection $orders
 *
 * @property int holding_id ID Холдинка
 *
 * @package Modules\User\Entities
 */
class Contact extends Model
{
    use ParamsTrait;

    protected $fillable = [
        'guid', 'guid_site', 'name', 'position', 'holding_id',

        // мутаторы
        'address', 'phone', 'email'
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function holding()
    {
        return $this->belongsTo(Holding::class, 'holding_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->holding_id)) {
                $model->holding()->associate(
                    Holding::create(['name' => $model->name])
                );
            }

            $model->guid_site = Str::uuid();
        });
    }

    /**
     * Получает массив ошибок при приглашении пользователя.
     *
     * @return array|string[]
     */
    public function getInviteUserErrors() {
        $errors = [];

        $fields = ['email', 'phone'];

        $filed_labels = [
          'email' => 'Email',
          'phone' => 'Телефон',
        ];

        $error_format = '%s уже использует для авторизациии %s %s';

        foreach ($fields as $field) {
            $field_value = $this->{$field};

            if (!$field_value) {
                return ["Для приглашения пользователя необходимо заполнить {$filed_labels[$field]}"];
            }

            $user = User::firstWhere($field, $field_value);

            if (!$user) {
                continue;
            }

            $errors[] = sprintf(
                $error_format,
                $user->name,
                $filed_labels[$field],
                $field_value
            );
        }

        return $errors;
    }
}
