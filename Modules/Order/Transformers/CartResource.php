<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{

    public static $wrap = null;

//    public function toArray($request)
//    {
//
//        $result = [
//            'cart_id' => $this->id,
//        ];
//
//        return $result;
//    }
}
