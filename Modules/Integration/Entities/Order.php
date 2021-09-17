<?php

namespace Modules\Integration\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationTrait;
use Modules\Order\Entities\Order as BaseOrder;

class Order extends BaseOrder implements IntegrationInterface
{
    use IntegrationTrait;


    public static function prepareData(array $attrs): array
    {
        return [
            'contract_title' => ArrHelper::clearData($attrs, 'НаименованиеДоговора'),
//            'contract_date_end' => ArrHelper::clearData($attrs, 'ДатаОкончанияДоговора', 'date'),
//            'contract_date' => ArrHelper::clearData($attrs, 'ДатаДоговора', 'date'),
            'contract_number' => ArrHelper::clearData($attrs, 'НомерДоговора'),
            'contract_status' => ArrHelper::clearData($attrs, 'СтатусДоговора'),
            'delay_days' => ArrHelper::clearData($attrs, 'ПросрочкаДней'),
            'pay_before' => ArrHelper::clearData($attrs, 'ОплатитьДо', 'date'),
            'debt' => ArrHelper::clearData($attrs, 'Долг', 'float', 0),
            'sum_debt' => ArrHelper::clearData($attrs, 'СумммаДолга', 'float', 0),
            'is_paid' => ArrHelper::clearData($attrs, 'Оплачен') === 'Да',
            'address' => ArrHelper::clearData($attrs, 'АдресДоставки'),
            'email' => ArrHelper::clearData($attrs, 'EmailКотнрагента'),
            'kpp' => ArrHelper::clearData($attrs, 'КППКонтрагента'),
            'inn' => ArrHelper::clearData($attrs, 'ИННКонтрагента'),
            'code' => ArrHelper::clearData($attrs, 'КодКонтрагента'),
            'name' => ArrHelper::clearData($attrs, 'НаименованиеКонтрагента'),
            'percent' => ArrHelper::clearData($attrs, 'ПроцентОтгрузки', 'float', 0),
            'sum_document' => ArrHelper::clearData($attrs, 'СуммаДокументаЗаказа', 'float', 0),
            'order_status' => ArrHelper::clearData($attrs, 'СтатусЗаказа'),
//            'order_date' => ArrHelper::clearData($attrs, 'ДатаЗаказа', 'date'),
            'id_1c' => ArrHelper::clearData($attrs, 'НомерЗаказа'),
        ];
    }

}
