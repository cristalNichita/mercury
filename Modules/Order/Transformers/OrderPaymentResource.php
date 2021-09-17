<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Order\Classes\OrderWorkflow;

class OrderPaymentResource extends JsonResource
{
    public function toArray($request)
    {
        $payment = OrderWorkflow::getPaymentFromOrder($this->order);

        $result = [
            'id' => $this->id,
            'status' => $this->status,
            'code' => $this->code,
            'price' => $this->price,
            'params' => Arr::except($this->params, ['form']),
            'form' => $payment->createPaymentForm()
        ];

        return $result;
    }
}
