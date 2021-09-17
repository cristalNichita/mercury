<?php


namespace Modules\Catalog\Filters;


use Illuminate\Support\Arr;

/**
 * Class ProductFilter
 * @package Modules\Catalog\Filters
 * @deprecated
 */
class ProductFilter extends QueryFilter
{

    public function title($value) {
        $this->builder->where('title', 'like', "%{$value}%");
    }

    public function description($value) {
        $this->builder->where('description', 'like', "%{$value}%");
    }

    public function articleNumber($value) {
        $this->builder->where('article_number', '=', "$value");
    }

    public function isNew($value) {
        $this->builder->where('is_new', (bool) $value);

    }

    public function isOffer($value) {
        $this->builder->where('is_offer', (bool) $value);
    }
    public function isSale($value) {
        $this->builder->where('is_offer', (bool) $value);
    }

    public function discount($value) {
        $this->builder->where('discount', $value);
    }

    public function categories(...$values) {
        if(!is_array($values)) {
            $values = [ $values ];
        }

        $this->builder->whereHas('categories', function($query) use($values){
            return $query->whereIn('categories.id', $values);
        });
    }

    public function parameters(...$values) {
        if(!is_array($values)) {
            $values = [ $values ];
        }

        $this->builder->whereHas('parameters', function($query) use($values){
            return $query->whereIn('parameters.id', $values);
        });
    }


    public function parametersValues(...$values) {
        if(!is_array($values)) {
            $values = [ $values ];
        }

        $this->builder->whereHas('parameters_values', function($query) use($values){
            foreach($values as $value) {
                $query->where('parameter_values.id', $value);
            }

            return $query;
        });

    }



    protected function date_preparation($request) {
        $row_data = $request->all();

        $translate = [
            'c' => 'categories',
            'p' => 'parameters',
            'pv' => 'parameters_values',
            'b'  => 'brands'
        ];

        $filtered = Arr::except($row_data, array_keys($translate));


        foreach($translate as $key => $t_key) {

            if( !empty($row_data[$key]) ) {

                $filtered[$t_key] = $row_data[$key];

                if($key === 'pv') {
                    $filtered[$t_key] = Arr::flatten( $filtered[$t_key] );
                }

            }

        }

        return $filtered;
    }

}
