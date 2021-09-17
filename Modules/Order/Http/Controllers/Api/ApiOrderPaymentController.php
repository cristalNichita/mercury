<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Order\Classes\OrderWorkflow;
use Modules\Order\Entities\Order;
use Modules\Order\Events\PaymentChangeStatus;

//use Settings;

class ApiOrderPaymentController extends BaseController
{
    public function __invoke(Request $request)
    {
        $payment_result = $request->all();

        $order = Order::find((int) $payment_result['Order_ID']);
        if ($order) {
            $payment = OrderWorkflow::getPaymentFromOrder($order);

            if ($payment->checkHash($payment_result)) {
                $payment->setUnitellerStatus($order->payment()->first(), $payment_result['Status']);
                event(new PaymentChangeStatus($payment));

                OrderWorkflow::setOrderStatus($order, 'success_payment');
            }
        }

        Log::debug('ResultPayment',$payment_result);
        return response('OK');
    }
}
