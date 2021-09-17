<?php


namespace Modules\Catalog\Filters;

/**
 * Class BrandFilter
 * @package Modules\Catalog\Filters
 * @deprecated
 */
class BrandFilter extends QueryFilter
{

    public function title($value)
    {
        $this->builder->where('title', 'like', "%{$value}%");
    }

}
