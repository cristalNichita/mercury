<?php


namespace Modules\Order\Classes;


use Illuminate\Support\Str;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Modules\Order\Events\OrderChangeStatus;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Helpers\CartHelper;
use Modules\User\Entities\Contact;

class OrderWorkflow
{

    public static function createCode($id)
    {
        return "САЙТ-" . str_pad($id, 6, "0", STR_PAD_LEFT);
    }

    public static function getDeliveryParams($request)
    {
        $result = [];

        $all = $request->all();
        foreach ($all as $key => $value) {
            if (strpos($key, 'delivery') === 0) {
                $result[Str::camel(substr($key, 8))] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $type
     * @return OrderBasePayment|OrderOnlinePayment|OrderInvoicePayment
     */
    public static function getPaymentByType($type)
    {
        $class = 'Modules\Order\Classes\Order' . Str::ucfirst($type) . 'Payment';
        return new $class();
    }

    public static function getPaymentFromOrder($order)
    {
        $payment = OrderWorkflow::getPaymentByType($order->payment_type);
        return $payment->loadFromOrder($order);
    }

    public static function allowCancelOrder($order): bool
    {
        if ($order->status == 'new') {
            return true;
        }

        if ($order->status == 'process') {
            return true;
        }

        if ($order->status == 'waiting_payment') {
            return true;
        }

        return false;
    }

    /**
     * Установка нового статуса заказу, рассылка уведомлений и прочее
     * @param $order
     * @param $new_status
     * @return bool
     */
    public static function setOrderStatus($order, $new_status)
    {

        //  Тут логика перехода из одного статуса в другой
        switch ($new_status) {
            case 'cancel':
                if (!self::allowCancelOrder($order)) {
                    return false;
                }
                break;

            default:

        }

        $order->status = $new_status;
        $order->save();

        event(new OrderChangeStatus($order));

        return true;
    }

    public static function createFromSite($request, $contact = null, $company = null)
    {
        $order = new Order();
        $order->contact()->associate($contact);
        $order->company()->associate($company);
        $order->user()->associate($contact->user ?? null);
        $order->may_payment = 1;
        $order->save();

        $order->code = static::createCode($order->id);

        $cart = Cart::find($request->input('cart_id'));
        $order->fillFromCart($cart);

        $order->comment = $request->input('contactComment');

        //Todo: для процесса разработки корзину оставляем
//        $helper = new CartHelper($cart);
//        $helper->clear();

        $params = [];

        // Todo: Доставку пока ложим просто в параметры
        $params['delivery'] = static::getDeliveryParams($request);
        $order->delivery_cost = $request->input('deliveryCost') ?? 0;

        // Создаем оплату
        $payment = self::getPaymentByType($request->input('paymentType'));
        $payment->createFromRequest($order, $request);

        // Контакт может удалиться - а данные должны хранится
        $params['contactPhone'] = $request->input('contactPhone');
        $params['contactEmail'] = $request->input('contactEmail');
        $params['contactFio'] = $request->input('contactFio');

        $order->params = $params;
        $order->status = Payment::STATUS_WAITING;

        $order->save();

        event(new OrderCreated($order));
        return $order;
    }


}
