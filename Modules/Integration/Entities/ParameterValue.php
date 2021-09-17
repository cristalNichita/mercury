<?php


namespace Modules\Integration\Entities;

use Modules\Catalog\Entities\ParameterValue as ParameterValueBase;

class ParameterValue extends ParameterValueBase
{

    public static function fillFromParser(array $attributes, int $parameter_id = null) {

        //Warning: тут проще firstOrCreate
        return self::updateOrCreate(
            [
                'title' => $attributes['ЗначениеСвойства'],
                'product_parameter_id' => $parameter_id,
            ],
            [ ]
        );

    }


}
