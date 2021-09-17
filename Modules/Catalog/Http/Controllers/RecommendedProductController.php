<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\RecommendedProduct;

class RecommendedProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Product $parent_product = null)
    {
        if (is_null($parent_product)) {
            $recommended_products = RecommendedProduct::with('product')
                                            ->goodsDay()
                                            ->paginate($this->per_page);
        } else {
            $recommended_products = $parent_product->recommendation()
                ->with('recommendedProduct')
                ->get()
                ->pluck('recommendedProduct');
        }

        return Inertia::render('Catalog/RecommendedProduct/RecommendedProductIndex', [
            'products' => $recommended_products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request, Product $parent_product = null)
    {
        $recommended = (int) $request->get('product');

        if (is_null($parent_product)) {
            $rProduct = RecommendedProduct::where('product_id', '=', $recommended)
                ->goodsDay()
                ->firstOrCreate(['product_id' => $recommended]);
        } else {
            $rProduct = RecommendedProduct::where('product_id', '=', $parent_product->id)
                ->where('recommended_product_id', '=', $recommended)
                ->firstOrCreate(['product_id' => $parent_product->id, 'recommended_product_id' => $recommended]);
        }


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param RecommendedProduct $product
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(RecommendedProduct $product)
    {
        $product->delete();
        return redirect()->back();
    }
}
