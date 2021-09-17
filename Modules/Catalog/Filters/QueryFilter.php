<?php


namespace Modules\Catalog\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class QueryFilter
 * @package Modules\Catalog\Filters
 * @deprecated
 */
abstract class QueryFilter
{
    protected $data;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->data = $this->date_preparation($request);
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->data as $field => $value) {
            $method = Str::camel($field);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }
    }

    protected function date_preparation($request) {
        return $request->all();
    }
}
