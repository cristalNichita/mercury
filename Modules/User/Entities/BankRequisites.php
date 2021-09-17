<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Банковские реквизиты компании
 * @package Modules\User\Entities
 * @property int $id
 * @property int company_id ID Компании
 * @property string $name Наименование банка
 * @property string $bik БИК банка
 * @property string $invoice Расчетынй счет
 * @property string $kor Кор. счет
 * @property boolean $closed Признак закрытия счета (1 - закрыт | 0 - открыт)
 * @property boolean $default Счет используемый по умолчанию (1 - да | 0 - нет)
 */
class BankRequisites extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bik',
        'invoice',
        'kor',
        'closed',
        'default'
    ];

    protected static function newFactory()
    {
        return \Modules\User\Database\factories\BankRequisitesFactory::new();
    }

    public static function default () {
        return static::where('default', '=', 1);
    }
}
