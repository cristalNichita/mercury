<?php


namespace Modules\Catalog\Filters;

/**
 * Class CategoryFilter
 * @package Modules\Catalog\Filters
 * @deprecated
 */
class CategoryFilter extends QueryFilter
{

    public function title($value) {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function active($value) {
        $this->builder->where('active', (bool) $value);
    }

    protected function date_preparation($request) {

        return $request->all();
    }

}
