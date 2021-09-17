<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ContactParam
 * @package Modules\User\Entities
 * @property-read Company|Contact $parent Родитель параметра (связь морф)
 * @property string $type Тип (phone|email|address)
 */
class ContactParam extends Model
{

    public $timestamps = false;

    protected $fillable = ['type', 'view', 'value', 'value_1c'];

    public function parent()
    {
        return $this->morphTo();
    }
}
