<?php

namespace Modules\Order\Classes;

use Modules\Order\Entities\Order;
use Modules\Order\Traits\UnitellerPayment;

class OrderOnlinePayment extends OrderBasePayment
{
    protected $_type = 'online';

    use UnitellerPayment;

    public function createPaymentForm()
    {
        $params = $this->payment->params;

        $params['form'] = $this->_createPaymentForm($this->order->id, $this->payment->price);

        $this->payment->params = $params;
        $this->payment->save();

        return $params['form'];
    }


    public function createFromRequest($order, $request)
    {
        parent::createFromRequest($order, $request);

        $fill = [
            'code' => $this->_type,
            'price' => $order->total
        ];

        $this->payment = $order->payment()->create($fill);

        $this->createPaymentForm();
    }
}
