<?php


namespace Modules\Integration\Classes;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\ParameterValue;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\ProductParameter;
use Modules\Integration\Helpers\OdinHelper;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Entities\Holding;

class UsersParser extends BaseParser
{

    public function import($xml)
    {
        $this->holdings = Holding::all()->keyBy('id_1c');
        foreach ($xml->Партнер as $xml_holding) {

            $holding = $this->prepareHolding($xml_holding);
            $holding = $this->syncHolding($holding);

            foreach ($xml_holding->КонтактныеЛица->КонтактноеЛицо as $xml_contact) {

                $contact = $this->prepareContact($xml_contact, $holding);
                $contact_model = $this->syncContact($contact);

                $this->syncParams($contact_model, $contact['params']);

            }

            foreach ($xml_holding->Контрагенты->Контрагент as $xml_company) {

                $company = $this->prepareCompany($xml_company, $holding);
                $company_model = $this->syncCompany($company);

                $this->syncParams($company_model, $company['params']);

                $this->syncBanks($company_model, $company['banks']);

            }
        }
    }

    protected function syncHolding($holding)
    {
        // Сперва ищем по id_1c
        $find = Holding::where('id_1c', $holding['id_1c'])->first();

        // Пробиваем обратную синхронизацию
        if (empty($find) && !empty($holding['guid_site'])) {
            $find = Holding::where('guid_site', $holding['guid_site'])->first();
        }

        // Если ничего не найдено - создаем
        if (empty($find)) {
            $find = Holding::create($holding);
        } else {
            $find->update($holding);
        }

        return $find;
    }

    protected function syncContact($contact)
    {
        // Сперва ищем по guid
        $find = Contact::where('guid', $contact['guid'])->first();

        // Пробиваем обратную синхронизацию
        if (empty($find) && !empty($contact['guid_site'])) {
            $find = Contact::where('guid_site', $contact['guid_site'])->first();
        }

        // Если ничего не найдено - создаем
        if (empty($find)) {
            $find = Contact::create($contact);
        } else {
            $find->update($contact);
        }

        return $find;
    }

    protected function syncCompany($company)
    {

        // Сперва ищем по id_1c
        $find = Company::where('guid', $company['guid'])->first();

        // Пробиваем обратную синхронизацию
        if (empty($find) && !empty($company['guid_site'])) {
            $find = Company::where('guid_site', $company['guid_site'])->first();
        }

        // Если ничего не найдено - создаем
        if (empty($find)) {
            $find = Company::create($company);
        } else {
            $find->update($company);
        }

        return $find;
    }

    /**
     * @param $model Contact|Company
     * @param $params array
     */
    protected function syncParams($model, $params)
    {
        // Удаляем параметры 1с
        $model->params()->whereNotNull('value_1c')->delete();

        foreach ($params as $param) {
            $model->params()->create($param);
        }
    }

    protected function prepareHolding($xml)
    {
        return [
            'id_1c' => trim((string)$xml['Код']),
            'guid_site' => trim((string)$xml['GUID_Сайт']),
            'name' => trim((string)$xml['Наименование']),
            'contact_guid' => trim((string)$xml['ОсновноеКонтактноеЛицоGUID']),
            'deleted' => ((string)$xml['ПометкаУдаления'] === 'Да'),
        ];
    }

    protected function prepareContact($xml, $holding)
    {
        $result = [
            'guid' => trim((string)$xml['GUID']),
            'guid_site' => trim((string)$xml['GUID_Сайт']),
            'name' => trim((string)$xml['Имя']),
            'position' => trim((string)$xml['Должность']),
            'holding_id' => $holding->id,
            'deleted' => ((string)$xml['ПометкаУдаления'] === 'Да'),
        ];

        $result['params'] = $this->prepareParams($xml->КонтактнаяИнформацияКЛ);

        return $result;
    }

    protected function prepareCompany($xml, $holding)
    {
        $result = [
            'guid' => trim((string)$xml['GUID']),
            'guid_site' => trim((string)$xml['GUID_Сайт']),
            'name' => trim((string)$xml['Наименование']),
            'type_1c' => trim((string)$xml['ТипКл']),
            'inn' => trim((string)$xml['ИННКонтрагента']),
            'kpp' => trim((string)$xml['КППКонтрагента']),
            'holding_id' => $holding->id,
            'deleted' => ((string)$xml['ПометкаУдаления'] === 'Да'),
        ];

        $result['type'] = ($result['type_1c'] === 'Физическое лицо') ? Company::FIS : Company::URI;

        $result['params'] = $this->prepareParams($xml->КонтактнаяИнформацияЮЛ);

        $result['banks'] = $this->prepareBanks($xml->БанковскиеСчета);

        return $result;
    }

    protected function prepareParams($xml)
    {
        $result = [];
        foreach ($xml->СтрокаКИ as $xml_param) {
            $view = trim((string)$xml_param['Вид']);
            $type = trim((string)$xml_param['Тип']);

            if (($view == 'Электронная почта') || ($view == 'Телефон')) {
                $view = '';
            }

            $param = [
                'type' => OdinHelper::convertParamType($type),
                'value_1c' => trim((string)$xml_param['Значение']),
                'value' => trim((string)$xml_param['Значение']),
                'view' => ($view !== $type) ? $view : ''
            ];

            if ($param['type'] === 'phone') {
                $param['value'] = Helper::clearPhone($param['value']);
            }

            $result[] = $param;
        }
        return $result;
    }

    protected function prepareBanks($xml)
    {
        $result = [];
        foreach ($xml->Счет as $xml_bank) {
            $closed = trim((string)$xml_bank['Закрыт']);
            $name = trim((string)$xml_bank['Банк']);
            $title = trim((string)$xml_bank['Наименование']);

            $bank = [
                'name' => !empty($name) ? $name : $title,
                'bik' => trim((string)$xml_bank['БИКБанка']),
                'invoice' => trim((string)$xml_bank['НомерСчета']),
                'kor' => trim((string)$xml_bank['КоррСчетБанка']),
                'closed' => ($closed === 'Да') ? true : false
            ];

            $result[] = $bank;
        }
        return $result;
    }

    protected function syncBanks($model, $banks)
    {
        foreach ($banks as $bank) {
            $model->bankRequisites()->updateOrCreate(
                [
                    'bik' => $bank['bik'],
                    'invoice' => $bank['invoice']
                ],
                $bank
            );
        }
    }
}
