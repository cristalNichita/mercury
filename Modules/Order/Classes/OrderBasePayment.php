<?php


namespace Modules\Order\Classes;


use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderPayment;

class OrderBasePayment
{
    protected $_type = 'base';

    /** @var Order */
    public $order;

    /** @var OrderPayment */
    public $payment;

    public function createFromRequest($order, $request)
    {
        $this->order = $order;
        $order->payment_type = $this->_type;
    }

    public function loadFromOrder($order)
    {
        $this->order = $order;
        $this->payment = $order->payment;
        return $this;
    }


    public function toArray(): array
    {
        return $this->payment->toArray() ?? [];
    }

    public function createPaymentForm()
    {
        return [];
    }

}
