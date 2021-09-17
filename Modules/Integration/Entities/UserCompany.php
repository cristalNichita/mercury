<?php


namespace Modules\Integration\Entities;


use Illuminate\Support\Arr;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationFileTrait;
use Modules\Integration\Traits\IntegrationTrait;
use Modules\User\Entities\Company;

class UserCompany extends Company implements IntegrationInterface, IntegrationFileInterface
{

    use IntegrationTrait, IntegrationFileTrait;


    public static function prepareData(array $attrs = []): array
    {

        return [
            'guid' => ArrHelper::clearData($attrs, 'GUID'),
            'holding_id' => ArrHelper::clearData($attrs, 'holding_id'),
            'name' => ArrHelper::clearData($attrs, 'Наименование'),
            'deleted' => $attrs['ПометкаУдаления'] === 'Да',
            'inn' => ArrHelper::clearData($attrs, 'ИННКонтрагента'),
            'kpp' => ArrHelper::clearData($attrs, 'КППКонтрагента'),
            'type' => ArrHelper::clearData($attrs, 'ТипКл'),
            'legal_address' => ArrHelper::clearData($attrs, 'Юридический адрес'),
            'actual_address' => ArrHelper::clearData($attrs, 'Фактический адрес'),
            'phone' => ArrHelper::clearData($attrs, 'Телефон'),
            'email' => ArrHelper::clearData($attrs, 'Электронная почта'),
        ];
    }

    public static function integrationKey(): string
    {
        return 'guid';
    }
}
