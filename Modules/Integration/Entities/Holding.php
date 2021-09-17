<?php


namespace Modules\Integration\Entities;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\Interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationFileTrait;
use Modules\Integration\Traits\IntegrationTrait;
use Modules\User\Entities\Company;
use Modules\User\Entities\Holding as BaseHoldingCompany;

class Holding extends BaseHoldingCompany implements IntegrationInterface, IntegrationFileInterface
{

    use IntegrationTrait, IntegrationFileTrait;


    public static function prepareData(array $attrs = []): array
    {

        return [
            'id_1c' => ArrHelper::clearData($attrs, 'Код'),
            'name' => ArrHelper::clearData($attrs, 'Наименование'),
            'deleted' => $attrs['ПометкаУдаления'] === 'Да',
            'contact_person_guid' => ArrHelper::clearData($attrs, 'ОсновноеКонтактноеЛицоGUID'),
        ];
    }
}
