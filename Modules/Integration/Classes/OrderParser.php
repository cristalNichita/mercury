<?php


namespace Modules\Integration\Classes;


use Illuminate\Support\Collection;
use Modules\Integration\Entities\{Order, OrderItem, Product, User};
use Modules\Integration\Helpers\CreatedRow;
use Modules\Integration\Helpers\ExistRow;
use Modules\Integration\Helpers\UpdateRow;

/**
 * Class OrderParser
 * @package Modules\Integration\Classes
 */
class OrderParser extends AbstractParser
{

    protected $filesystem_local_path_key = 'orders';
    protected $products;
    protected $orders;
    protected $users;




    protected function importRows(Collection $file)
    {

        $new_orders = $file->get('Заказ');
        if(!($new_orders instanceof Collection)) {
            $new_orders = collect([ $new_orders ]);
        }

        $created = [];
        $updated = [];
        $exist   = [];
        $deleted = [];

        $this->users = User::whereIn( User::integrationKey(), $new_orders->pluck('КодКонтрагента') )->get()->keyBy('guid');

        foreach($new_orders as $order) {

            $result = $this->importRow($order);

            if($result instanceof ExistRow) {
                $exist[] = $result->model->integration_code;
                $this->addLog("Заказ с номером: {$result->model->integration_code} не изменен");
            }
            elseif($result instanceof UpdateRow) {
                $updated[] = $result->model->integration_code;
                $this->addLog("Обневлен заказ с номером: {$result->model->integration_code}");
            }
            elseif($result instanceof CreatedRow) {
                $created[] = $result->model->integration_code;
                $this->addLog("Создан заказ с номером: {$result->model->integration_code}");
            }

            $items = $result->model->items->keyBy('id_1c');
            $new_items = data_get($order, 'ТЧЗаказа.СтрокаТЧ', []);
            if(!empty($new_items['Сумма'])) {
                $new_items = [ $new_items ];
            }
            $this->importRowsItems($new_items, $items, $result->model);
        }

        if($this->full) {

            $merge = array_merge($created, $updated, $exist);

            Order::whereNotIn('id_1c', $merge)->chunk(300, function($models) use (&$deleted){

                foreach($models as $model) {
                    $deleted[] = $model->integration_code;
                    $model->delete();
                }

            });
            $this->addRowsToLog('Удалено заказов', $deleted);
        }


    }

    protected function importRow(array $data)
    {

        $prepareData = Order::prepareData($data);

        if($user = $this->users->get($prepareData['code'])) {
            $prepareData['user_id'] = $user->id;
        }

        $order = Order::findByCode($prepareData[ Order::integrationKey() ] );
        $result = null;

        if( !$order ) {
            $order = Order::create($prepareData);
            return new CreatedRow($order);
        }

        $order->fill($prepareData);

        if($order->isDirty()) {
            $order->save();
            return new UpdateRow($order);
        }
        return new ExistRow($order);
    }

    protected function importRowsItems($new_items, $items, $order) {

        $created = [];
        $updated = [];
        $exist   = [];
        $deleted = [];

        foreach($new_items as $new_item) {
            $result = $this->importRowItem($new_item, $items, $order);
            $code = $result->model->integration_code;

            if($result instanceof ExistRow) {
                $exist[$code] = 0;
            }
            elseif($result instanceof UpdateRow) {

                $updated[$code] = 0;
            }
            elseif($result instanceof CreatedRow) {
                $created[$code] = 0;
            }
        }

        $merge = array_merge($created, $updated, $exist);

        foreach($items as $key => $item) {

            if(!isset($merge[$key])) {

                $deleted[] = $key;
                $items->pull($key);
                $item->delete();
            }
        }

        $this->addRowsToLog('Создано позиций', $created);
        $this->addRowsToLog('Обновлено позиций', $updated);
        $this->addRowsToLog('Позиций без изменений', $exist);
        $this->addRowsToLog('Удалено позиций', $deleted);

    }

    protected function importRowItem(array $new_item, &$items, $order) {

        $prepareData = OrderItem::prepareData($new_item + ['order_id' => $order->id]);
        $item = $items->get( $prepareData[ OrderItem::integrationKey() ] );
        if(!$item) {
            $item = $order->items()->create($prepareData);
            return new CreatedRow($item);
        }

        $item->fill($prepareData);
        if($item->isDirty()) {
            $item->save();
            return new UpdateRow($item);
        }
        return new ExistRow($item);
    }
}
