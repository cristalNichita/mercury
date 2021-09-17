<?php


namespace Modules\Integration\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationTrait;
use Modules\Order\Entities\OrderItem as OrderItemBase;


class OrderItem extends OrderItemBase implements IntegrationInterface
{
    use IntegrationTrait;


    public static function prepareData(array $attrs): array
    {
        return [
            'sum' => ArrHelper::clearData($attrs, 'Сумма', 'float', 0),
            'count' => ArrHelper::clearData($attrs, 'Количество'),
            'price' => ArrHelper::clearData($attrs, 'Цена', 'float', 0),
            'nds' => ArrHelper::clearData($attrs, 'СтавкаНДС'),
            'status' => ArrHelper::clearData($attrs, 'Статус'),
            'product' => ArrHelper::clearData($attrs, 'Товар'),
            'id_1c' => ArrHelper::clearData($attrs, 'КодТовара'),
            'order_id' => ArrHelper::clearData($attrs, 'order_id'),
        ];
    }
}
