<?php


namespace Modules\Integration\Entities;

use App\Models\User as UserBase;
use Illuminate\Support\Arr;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationFileTrait;
use Modules\Integration\Traits\IntegrationTrait;


class User extends UserBase implements IntegrationInterface, IntegrationFileInterface
{

    use IntegrationTrait, IntegrationFileTrait;


    public static function prepareData(array $attrs): array
    {
        $fio = ArrHelper::getFIO($attrs['Имя']);


        return [
            'guid' => ArrHelper::clearData($attrs, 'GUID'),
            'lastname' => $fio['lastname'],
            'name' => $fio['name'],
            'middlename' => $fio['middlename'],
            'inn' => ArrHelper::clearData($attrs, 'ИННКонтрагента'),
            'kpp' => ArrHelper::clearData($attrs, 'КППКонтрагента'),
            'position_title' => ArrHelper::clearData($attrs, 'Должность'),
            'phone' => ArrHelper::clearData($attrs, 'Телефон', null, ''),
            'email' => ArrHelper::clearData($attrs, 'Электронная почта'),
            'deleted' => $attrs['ПометкаУдаления'] === 'Да',
        ];
    }


    public static function findByCodes(array $data) {

        $inner_id = (int) data_get($data, 'GUID_Сайт');
        $guid = data_get($data, 'GUID');

        if(!$inner_id && !$guid) {
            return null;
        }

        $query = self::query();

        if($inner_id) {
            $query->where('id', $inner_id);
        }

        elseif($guid) {
            $query->where('guid', $guid);
        }


        return $query->first();

    }

    public static function integrationKey(): string
    {
        return 'guid';
    }

}
