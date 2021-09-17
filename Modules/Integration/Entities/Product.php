<?php


namespace Modules\Integration\Entities;

use Modules\Catalog\Entities\Product as ProductBase;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\Interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationFileTrait;
use Modules\Integration\Traits\IntegrationTrait;


class Product extends ProductBase implements IntegrationInterface, IntegrationFileInterface
{

    use IntegrationTrait, IntegrationFileTrait;

    /** @deprecated */
    public static function fillFromParser(array $attributes, $category_id = null)
    {

        return self::updateOrCreate(
            [
                'id_1c' => $attributes['Код'],
            ],
            [
                'title' => $attributes['Наименование'],
                'description' => $attributes['Описание'],
                'price' => (float)$attributes['Цена'],
                'quantity' => (integer)$attributes['Остатки'],
                'weight' => (float)$attributes['Вес'],
                'volume' => (integer)$attributes['Объем'],
                'category_id' => $category_id,
            ]
        );

    }

    /** @deprecated */
    public function getIntegrationGalleryKeyAttribute(): string
    {
        return 'gallery';
    }

    /** @deprecated */
    public static function prepareData(array $attrs): array
    {
        return [
            'id_1c' => ArrHelper::clearData($attrs, 'Код'),
            'title' => ArrHelper::clearData($attrs, 'Наименование'),
            'description' => ArrHelper::clearData($attrs, 'Описание'),
            'price' => ArrHelper::clearData($attrs, 'Цена', 'float', 0),
            'quantity' => ArrHelper::clearData($attrs, 'Остатки', null, 0),
            'weight' => ArrHelper::clearData($attrs, 'Вес', 'float', 0),
            'volume' => ArrHelper::clearData($attrs, 'Объем', null, 0),
            'deleted' => ArrHelper::clearData($attrs, 'ПометкаУдаления') === 'Да',
        ];
    }
}
