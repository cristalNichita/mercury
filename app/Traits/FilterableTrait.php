<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait FilterableTrait
{
    /**
     * Фильтрация данных
     *
     * Передается массив данных для фильтрации вида ключ=>значение
     *
     * Сперва ищется функция вида filterКлючКамелСтиль - вызывается
     *
     * Далее обрабатывается ключ, если ключ заканчивается на:
     *
     *    From (например priceFrom) применяется оператор >=
     *    To (например priceTo) применяется оператор <=
     *    Not (например oldPriceNot) применяется оператор <>
     *
     * Поля не выполняююся если их нет в массиве fillable + ['id', 'created_at', 'updated_at']
     *
     * Если значение массив используется whereIn или whereNotIn
     *
     * Фильтр понимает отношения ManyToMany (например: categories=11)
     *
     * в отношениях можно использовать внешний ключ отличный от id, например: categories.slug
     * также работают все вышеперечисленные условия (From To и т.д.)
     *
     * @param $builder
     * @param array $filter
     * @return mixed
     *
     * @example
     * filter => {
     *   id => 100,
     *   article => [1212, 3233, 4333]
     *   priceFrom => 1000,
     *   priceTo => 9000,
     *   ratingFrom => 3,
     *   quantityNot => 0,
     *   categories.slug => category-title,
     *   categories._lftFrom = 3,
     *   categories._rgtTo = 32,
     * }
     *
     * @todo Добавить возможность условия ИЛИ
     */
    public function scopeFilter($builder, array $filter)
    {

        if (empty($filter)) {
            return $builder;
        }

        $allowed = array_merge($this->fillable, ['id', 'created_at', 'updated_at']);

        foreach ($filter as $field => $value) {

            $method = Str::camel("filter_$field");

            if (method_exists($this, $method)) {
                $this->$method($builder, $value);
            } else {

                $operand = '=';
                if (Str::endsWith($field, 'From')) {
                    $field = Str::substr($field, 0, -4);
                    $operand = '>=';
                } elseif (Str::endsWith($field, 'To')) {
                    $field = Str::substr($field, 0, -2);
                    $operand = '<=';
                } elseif (Str::endsWith($field, 'Not')) {
                    $field = Str::substr($field, 0, -3);
                    $operand = '<>';
                } elseif (Str::endsWith($field, 'Like')) {
                    $field = Str::substr($field, 0, -4);
                    $operand = 'like';
                    $value = "%$value%";
                }

                // поле вида categories.slug - для связей
                $foreignKey = 'id';
                if (Str::contains($field, '.')) {
                    [$field, $foreignKey] = explode('.', $field);
                }

                // Обычные параметры
                if (in_array($field, $allowed)) {
                    $this->_applyWhere($builder, $field, $value, $operand);
                } else {
                    // Связь Many to Many
                    try {
                        if ($this->$field instanceof Collection) {
                            $builder->whereHas($field, function ($query) use ($field, $value, $foreignKey, $operand) {
                                return $this->_applyWhere($query, "$field.$foreignKey", $value, $operand);
                            });
                        }
                    } catch (\Exception $e) {
                        // Пропускаем ошибки вида must return a relationship instance
                    }
                }
            }
        }

        return $builder;
    }

    public function _applyWhere($builder, $field, $value, $operand)
    {
        if (is_array($value)) {
            if ($operand === '<>') {
                $builder->whereNotIn($field, $value);
            } else {
                $builder->whereIn($field, $value);
            }
        } else {
            $builder->where($field, $operand, $value);
        }

        return $builder;
    }

    /**
     * Специфичный фильтр по названию
     *
     * @param $builder
     * @param $value
     */
    public function filterTitle($builder, $value)
    {
        $builder->where('title', 'like', "%{$value}%")
            ->orderByRaw("case
                when `title` LIKE '{$value}' then 1
                when `title` LIKE '{$value}%'  then 2
                when `title` LIKE '%{$value}%'  then 3
                else 4 end");
    }
}
