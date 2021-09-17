<?php

namespace Modules\Integration\Entities;

use App\Traits\ImagesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\Interfaces\IntegrationInterface;
use Modules\Integration\Traits\IntegrationFileTrait;
use Modules\Integration\Traits\IntegrationTrait;
use Spatie\MediaLibrary\HasMedia;
use Modules\User\Entities\Document as BaseDocument;

class Document extends BaseDocument implements IntegrationInterface, IntegrationFileInterface
{
    use IntegrationTrait, IntegrationFileTrait;

    public static function integrationKey(): string
    {
        return 'guid';
    }

    public static function prepareData(array $attrs): array
    {
        return [
            'guid' => ArrHelper::clearData($attrs, 'GUID'),
            'name' => ArrHelper::clearData($attrs, 'Наименование'),
            'deleted' => $attrs['ПометкаУдаления'] === 'Да',
            'type' => ArrHelper::clearData($attrs, 'ТипКл'),
            'legal_address' => ArrHelper::clearData($attrs, 'Юридический адрес'),
            'actual_address' => ArrHelper::clearData($attrs, 'Фактический адрес'),
            'phone' => ArrHelper::clearData($attrs, 'Телефон'),
            'email' => ArrHelper::clearData($attrs, 'Электронная почта'),
        ];
    }

    public function documentable() {

        return $this->morphTo();

    }
}
