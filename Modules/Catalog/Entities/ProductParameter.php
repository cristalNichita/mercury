<?php

namespace Modules\Catalog\Entities;

use App\Traits\FilterableTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductParameter extends Model
{
    use SortableTrait;

    protected $fillable = ['title', 'id_1c'];

    public $timestamps = false;

    /**
     * Связь до значений
     * @return HasMany
     */
    public function values()
    {
        return $this->hasMany(ParameterValue::class);
    }
}
