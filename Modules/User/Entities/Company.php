<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\User\Traits\ParamsTrait;

/**
 * Компания (Контрагент в 1С)
 * @package Modules\User\Entities
 * @property int $id
 * @property int holding_id ID Холдинка
 * @property string $name Наименование компании/ФИО Физического лица
 * @property string $guid GUID идентификатор 1С
 * @property string $guid_site GUID идентификатор созданный сайтом
 * @property int $type Тип 0-Физлица 1-Юридические компании
 * @property string $type_1c Тип в 1С
 * @property string $inn ИНН
 * @property string $kpp КПП
 * @property string $ogrn ОГРН
 * @property-read Holding $holding Холдинг
 * @property-read Collection $orders Заказы оформленные на компанию
 * @property-read Collection $bankRequisites Банковские реквизиты компании
 */
class Company extends Model
{
    use ParamsTrait;
    use HasFactory;

    /** @var int Физическое лицо */
    public const FIS = 0;

    /** @var int Юридическое лицо (включая ИП) */
    public const URI = 1;

    protected $fillable = [

        'holding_id', 'name',
        'guid', 'guid_site',
        'type', 'type_1c',
        'inn', 'kpp', 'ogrn',

        // мутаторы
        'address', 'phone', 'email', 'u_address', 'f_address',
    ];

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\CompanyFactory::new();
    }

    /**
     * @return MorphMany
     * @deprecated ?
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Холдинг (группа компаний)
     * @return BelongsTo
     */
    public function holding()
    {
        return $this->belongsTo(Holding::class, 'holding_id');
    }

    /**
     * Заказы оформленные на компанию
     * @return HasMany
     */
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
     * Ссылка на банковские реквизиты
     * @return HasMany
     */
    public function bankRequisites()
    {
        return $this->hasMany(BankRequisites::class);
    }
}
