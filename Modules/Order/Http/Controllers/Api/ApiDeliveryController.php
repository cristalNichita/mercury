<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use CdekSDK\CdekClient;
use CdekSDK\LaravelCdekServiceProvider;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Modules\Order\Classes\CdekService;
use Modules\Order\Entities\Cart;
use Modules\Order\Entities\Delivery;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\StoreHouse;
use Modules\Order\Http\Requests\DeliveryCalculateRequest;
use Psy\Util\Str;
use CdekSDK\Requests;

class ApiDeliveryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {

        if($request->get('type') === 'transport company' || true) {
            $deliveries = Delivery::active()->orderBy('sort')->get();
        } else {

            $deliveries = StoreHouse::active()->orderBy('sort')->get();
        }

        return $this->sendSuccess($deliveries);
    }


    public function getRegions(Delivery $delivery) {
        return $this->sendSuccess($delivery->service->getRegions());
    }

    public function getCities(Request $request, Delivery $delivery) {

        $regionCode = (int)$request->get('regionCode') ?: 72;

        $result = [];

        if($delivery->service->isRegionCode($regionCode)) {
            $result = $delivery->service->getCities($regionCode);
        }

        return $this->sendSuccess($result);

    }

    /**
     * Склады
     * @param Request $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPVZList(Request $request, Delivery $delivery) {

        $regionCode = (int)$request->get('regionCode') ?: null;
        $cityCode = (int)$request->get('cityCode') ?: null;

        $result = [];

        if($delivery->service->isCityCode($regionCode, $cityCode)) {
            $result = $delivery->service->getPvz($cityCode);
        }
        return $this->sendSuccess($result);

    }

    public function calculate(DeliveryCalculateRequest $request, Delivery $delivery) {

        $validated = $request->validated();

        // Для теста пока закоментировапл, потом заменить
//        $cart = Cart::where('user_id', auth()->user()->id)->with('items')->findOrFail($validated['cart_id']);
        $cart = Cart::with('items.product')->findOrFail($validated['cart_id']);

        return $this->sendSuccess($delivery->service->calculate($validated, $cart));

    }
}
