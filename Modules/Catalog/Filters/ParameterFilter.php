<?php

namespace Modules\Catalog\Filters;
/**
 * Class ParameterFilter
 * @package Modules\Catalog\Filters
 * @deprecated
 */
class ParameterFilter extends QueryFilter {

    public function title($value) {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function active($value) {
        $this->builder->where('active', (bool) $value);
    }

}
