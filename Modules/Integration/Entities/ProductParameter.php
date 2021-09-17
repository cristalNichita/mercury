<?php


namespace Modules\Integration\Entities;

use Modules\Catalog\Entities\ProductParameter as ProductParameterBase;

class ProductParameter extends ProductParameterBase
{

    public static function fillFromParser(array $attributes) {

        return self::updateOrCreate(
            [
                'id_1c' => $attributes['Имя'],
            ],
            [
                'title' => $attributes['Свойство'],
            ]
        );

    }

}
