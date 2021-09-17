<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParameterValue extends Model
{
    public $timestamps = false;

    protected $fillable = ['value', 'product_parameter_id'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'parameter_values_2_products');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ProductParameter::class, 'product_parameter_id');
    }
}
