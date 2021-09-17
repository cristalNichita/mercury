<?php


namespace Modules\Integration\Services;


use Illuminate\Support\Facades\Storage;
use Modules\Integration\Helpers\OdinHelper;
use Modules\Order\Entities\Order;

class OdinService
{

    public function __construct()
    {

    }

    /**
     * @param $holding
     * @todo static - для удобства разработки, потом переделать на фасады
     */
    public static function pushHolding($holding)
    {
        $holding->load(['contacts.params', 'companies.params', 'companies.bankRequisites']);

        $xml = static::createXml('ДанныеОКлиенте');

        static::addHolding($xml, $holding);

        static::save($xml, 'users', 'change');
    }

    public static function pushOrder($order)
    {
        $xml = static::createXml('Заказы');

        static::addOrder($xml, $order);

        static::save($xml, 'orders', 'change');
    }

    public static function createXml($root): \SimpleXMLElement
    {
        return new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><' . $root . '></' . $root . '>');
    }

    public static function addHolding($xml, $holding)
    {

        $xml_holding = $xml->addChild('Партнер');

        $xml_holding->addAttribute('Код', $holding->id_1c);
        $xml_holding->addAttribute('GUID_Сайт', $holding->guid_site);
        $xml_holding->addAttribute('Наименование', $holding->name);
        $xml_holding->addAttribute('ПометкаУдаления', 'Нет');

        $xml_companies = $xml_holding->addChild('Контрагенты');
        foreach ($holding->companies as $company) {
            self::addCompany($xml_companies, $company);
        }

        $xml_contacts = $xml_holding->addChild('КонтактныеЛица');
        foreach ($holding->contacts as $contact) {
            static::addContact($xml_contacts, $contact);
        }
    }

    public static function addContact($xml, $contact)
    {
        $xml_contact = $xml->addChild('КонтактноеЛицо');

        $xml_contact->addAttribute('GUID', $contact->guid);
        $xml_contact->addAttribute('GUID_Сайт', $contact->guid_site);
        $xml_contact->addAttribute('Имя', $contact->name);
        $xml_contact->addAttribute('Должность', $contact->position);
        $xml_contact->addAttribute('ПометкаУдаления', 'Нет');

        $xml_params = $xml_contact->addChild('КонтактнаяИнформацияКЛ');
        static::addParams($xml_params, $contact->params);
    }

    public static function addCompany($xml, $company)
    {
        $xml_company = $xml->addChild('Контрагент');

        $xml_company->addAttribute('GUID', $company->guid);
        $xml_company->addAttribute('GUID_Сайт', $company->guid_site);
        $xml_company->addAttribute('Наименование', $company->name);
        $xml_company->addAttribute('ТипКл', $company->type_1c);
        $xml_company->addAttribute('ИННКонтрагента', $company->inn);
        $xml_company->addAttribute('КППКонтрагента', $company->kpp);

        $xml_company->addAttribute('ПометкаУдаления', 'Нет');
        $xml_params = $xml_company->addChild('КонтактнаяИнформацияЮЛ');
        static::addParams($xml_params, $company->params);

        $xml_bank = $xml_company->addChild('БанковскиеСчета');

        foreach ($company->bankRequisites as $bank) {
            $xml_invoice = $xml_bank->addChild('Счет');
            $xml_invoice->addAttribute('Наименоваие', $bank->name);
            $xml_invoice->addAttribute('Банк', $bank->name);
            $xml_invoice->addAttribute('НомерСчета', str_replace('-','', $bank->invoice));
            $xml_invoice->addAttribute('БИКБанка', $bank->bik);
            $xml_invoice->addAttribute('КоррСчетБанка', str_replace('-', '', $bank->kor));
            $xml_invoice->addAttribute('Закрыт', $bank->closed ? 'Да' : 'Нет');
            $xml_invoice->addAttribute('СВИФТБанка', '');
            $xml_invoice->addAttribute('Валюта', 'RUB');
        }

    }

    /**
     * @param $xml \SimpleXMLElement
     * @param $order Order заказ
     */
    public static function addOrder($xml, $order)
    {
        $xml_order = $xml->addChild('Заказ');
        $xml_order->addAttribute('ID_Сайт', $order->id);
        $xml_order->addAttribute('НомерЗаказа', $order->code);
        $xml_order->addAttribute('СтатусЗаказа', $order->status);
        $xml_order->addAttribute('СуммаЗаказа', $order->price);
        $xml_order->addAttribute('Скидка', $order->discount);
        $xml_order->addAttribute('Оплачен', $order->is_payment ? 'Да' : 'Нет');

        self::addContact($xml_order, $order->contact);
        self::addCompany($xml_order, $order->company);

        if ($order->payment) {
            self::addPayment($xml_order, $order->payment);
        }

        self::addDelivery($xml_order, $order);

        $new_child = $xml_order->addChild('Комментарий');
        if ($new_child !== NULL) {
            $node = dom_import_simplexml($new_child);
            $no = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($order->comment));
        }

        $xml_order->addAttribute('ДатаСоздания', $order->created_at);

        $xml_items = $xml_order->addChild('ТЧЗаказа');

        foreach ($order->items as $item) {

            $xml_item = $xml_items->addChild('СтрокаТЧ');
            $xml_item->addAttribute('ID_Сайт', $item->id);

            $xml_item->addAttribute('Сумма', $item->total);
            $xml_item->addAttribute('Количество', $item->count);
            $xml_item->addAttribute('Цена', $item->price);
            $xml_item->addAttribute('Товар', $item->name);

            if ($item->product) {
                $xml_item->addAttribute('КодТовара', $item->product->id_1c);
            }
        }
    }

    public static function addPayment($xml, $payment)
    {
        $xml_payment = $xml->addChild('Оплата');
        $xml_payment->addAttribute('ID_Сайт', $payment->id);
        $xml_payment->addAttribute('Тип', $payment->code);
        $xml_payment->addAttribute('Статус', $payment->status);
        $xml_payment->addAttribute('Сумма', $payment->price);

        if ($payment->code == 'invoice') {
            $xml_data = $xml_payment->addChild('Реквизиты');
            foreach ($payment->params as $name => $value) {
                $xml_data->addAttribute($name, $value);
            }
        }

        $xml_payment->addAttribute('ДатаСоздания', $payment->created_at);
    }

    public static function addDelivery($xml, $order)
    {
        $xml_delivery = $xml->addChild('Доставка');
        $xml_delivery->addAttribute('ТранспортнаяКомпания', 'sdek');
        $xml_delivery->addAttribute('Стоимость', $order->delivery_cost);

        $delivery = $order->params['delivery'] ?? [];

        foreach ($delivery as $name => $value) {
            if (is_array($value)) {
                $xml_data = $xml_delivery->addChild($name);
                foreach ($value as $child_name => $child_value) {
                    if (!is_array($child_value)) {
                        $xml_data->addAttribute($child_name, $child_value);
                    } else {
                        $xml_data->addAttribute($child_name, json_encode($child_value));
                    }
                }
            } else {
                $xml_delivery->addAttribute($name, $value);
            }
        }
    }

    public static function addParams($xml, $params)
    {
        foreach ($params as $param) {
            $xml_param = $xml->addChild('СтрокаКИ');

            $view = $param->view;
            if (($param->type === 'email') && empty($view)) {
                $view = 'Электронная почта';
            }

            if (($param->type === 'phone') && empty($view)) {
                $view = 'Телефон';
            }

            $xml_param->addAttribute('Тип', OdinHelper::reverseParamType($param->type));
            $xml_param->addAttribute('Вид', $view);
            $xml_param->addAttribute('Значение', $param->value_1c);
            $xml_param->addAttribute('ЗначениеСайт', $param->value);
        }
    }

    public static function save($xml, $folder, $type, array $files_paths = [], string $file_disk = 'public')
    {
        $timestamp = now()->format('Y_m_d_his_msu');
        $file = $timestamp . '_' . $folder . '_' . $type . '.xml';

        // Чтобы был читаемый xml а не одной строкой
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        Storage::disk('1c')->put("$folder/new/$file", $dom->saveXML());

        foreach ($files_paths as $path) {
            $file = Storage::disk($file_disk)->get($path);
            Storage::disk('1c')->put("$folder/new/" . basename($path), $file);
        }

    }


    public static function pushComplaint($complaint)
    {

        $xml = static::createXml('Рекламации');

        $xml->addChild('Рекламация');
        $xml->addAttribute('Статус', $complaint->state);
        $xml->addAttribute('IDСтатус', $complaint->status_id);
        $xml->addAttribute('IDТип', $complaint->type_id);
        $xml->addAttribute('Тип', $complaint->type);
        $xml->addAttribute('Описание', $complaint->description);
        $xml->addAttribute('Комментарий', $complaint->comment);

        $xml_files = $xml->addChild('Документы');

        foreach ($complaint->files as $file) {
            $xml_file = $xml_files->addChild('Документ');
            $xml_file->addAttribute('Название', $file->name);
        }


        $xml_order = $xml->addChild('Заказ');
        $xml_order->addAttribute('Код', $complaint->order->id_1c);

        $xml_contact = $xml->addChild('КонтактноеЛицо');
        $xml_contact->addAttribute('GUID', $complaint->user->guid);
        $xml_contact->addAttribute('GUID_Сайт', $complaint->user->guid_site);

        static::save($xml, 'complaints', 'change', $complaint->files->pluck('file_path')->all());
    }
}
