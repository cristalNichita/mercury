<?php


namespace Modules\Integration\Entities;

use Illuminate\Support\Collection;
use Modules\Catalog\Entities\Category as CategoryBase;

class Category extends CategoryBase
{

    public static function fillFromParser(array $attributes,  $parent_id = null) {

        return self::updateOrCreate([
            'id_1c' => $attributes['Код']
        ], [
            'title' => $attributes['Наименование'],
            'parent_id' => $parent_id
        ]);

    }


    public static function findByCode($code) {

        return self::where('id_1c', $code)->first();
    }

    public static function prepareData(array $attributes, $categories = []) {

        if( !empty($attributes['Родитель']) ) {

            $parent = $categories[ $attributes['Родитель'] ];
            $attributes['parent_id'] = $parent->id;
        }

        return [
            'title' => trim(data_get($attributes, 'Наименование', '')),
            'id_1c' => trim(data_get($attributes, 'Код', '')),
            'parent_id' => data_get($attributes, 'parent_id')
        ];
    }

    public function getIntegrationCodeAttribute()
    {
        return $this->id_1c;
    }

}
