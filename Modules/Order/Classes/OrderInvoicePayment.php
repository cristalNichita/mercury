<?php


namespace Modules\Order\Classes;

class OrderInvoicePayment extends OrderBasePayment
{
    protected $_type = 'invoice';

    public function createFromRequest($order, $request)
    {
        parent::createFromRequest($order, $request);

        $fill = [
            'code' => $this->_type,
            'price' => $order->total,
            'params' => $request->only([
                'companyBank',
                'companyBik',
                'companyInn',
                'companyKpp',
                'companyName',
                'companySchet'
            ])
        ];

        $this->payment = $order->payment()->create($fill);
    }

}
