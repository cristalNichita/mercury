<?php

namespace Modules\Catalog\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\RecommendedProduct;

class ApiRecommendedProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Product $product = null)
    {
        if (is_null($product)) {
            $recommended_products = RecommendedProduct::with('product')
                ->goodsDay()
                ->get()
                ->pluck('product');
        } else {
            $recommended_products = $product->recommendation()
                ->with('recommendedProduct')
                ->get()
                ->pluck('recommendedProduct');
        }

        return $this->sendSuccess($recommended_products);
    }
}
