<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Models\UserPayment;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Entities\Payment;
use App\Http\Controllers\BaseController;

class UnitellerController extends BaseController
{

    public function setResult() {
        $id = (int) request()->post('Order_ID');
        $payment = Payment::findOrFail($id);

        if(!$payment->checkHash(request()->post())) {

            abort(404);

        }

        $payment->setUnitellerStatus(request()->post('Status'));
    }

}
