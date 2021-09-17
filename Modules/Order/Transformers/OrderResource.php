<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Classes\OrderWorkflow;

class OrderResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray($request)
    {
        $this->load(['items', 'payment']);
        if ($this->contact) {
            $this->contact->append(['phone', 'email', 'address']);
        }

        $result = parent::toArray($request);

        $result['payment'] = new OrderPaymentResource($this->payment);

        $delivery = $this->params['delivery'] ?? null;

        $result['delivery'] = $delivery;
        unset($result['params']['delivery']);

        return $result;
    }
}
