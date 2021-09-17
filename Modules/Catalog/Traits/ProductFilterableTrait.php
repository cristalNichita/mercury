<?php

namespace Modules\Catalog\Traits;


use App\Traits\FilterableTrait;

trait ProductFilterableTrait
{
    use FilterableTrait;

    // Тут можно писать специфичные для модели методы фильтрации
    public function filterTitle($builder, $value)
    {
        if (empty($value)) {
            return;
        }
        $builder->where('title', 'like', "%{$value}%")
            ->orWhere('id_1c', 'like', "%{$value}%")
            ->orderByRaw("case
                when `title` LIKE '{$value}' then 1
                when `title` LIKE '{$value}%'  then 2
                when `title` LIKE '%{$value}%'  then 3
                else 4 end");

    }


}
