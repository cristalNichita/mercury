<?php


namespace App\Traits;


use Illuminate\Support\Str;

trait SortableTrait
{
    /**
     * Сортировка данных на основе параметра вида "столбец-направление"
     */
    public function scopeSort($builder, $sort)
    {
        $a_sort = explode('-', $sort);

        $column = $a_sort[0];
        $direction = $a_sort[1] ?? 'asc';

        if ($direction === 'ascending') {
            $direction = 'asc';
        }

        if ($direction === 'descending') {
            $direction = 'desc';
        }

        if (!in_array(Str::lower($direction), ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $allowed = array_merge($this->fillable, ['id', 'created_at', 'updated_at']);

        if (in_array($column, $allowed)) {
            $builder->reorder($column, $direction);
        }

        return $builder;
    }
}
