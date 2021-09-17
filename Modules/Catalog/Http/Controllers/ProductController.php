<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Filters\ProductFilter;

class ProductController extends BaseController
{

    public function index(Request $request)
    {
        $products = Product::with('brand')
            ->filter($request->get('filter', []))
            ->sort($request->get('sort'))
            ->paginate($this->per_page);

        return Inertia::render('Catalog/Products', [
            'filter' => $request->all(),
            'products' => $products
        ]);
    }

    public function indexResource(Request $request)
    {
        $filter = new ProductFilter($request->all());
        $products = Product::filter($filter)->with('brand')->paginate($this->paginate);
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('catalog::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::with(['parameters_values', 'recommendation.recommendedProduct'])->findOrFail($id);
        $product->append('gallery')->append('params');
        $recommended = $product->recommendation->whereNotNull('recommended_product_id');

        return Inertia::render('@Catalog/Product', [
            'product' => $product,
            'recommended' => $recommended
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('catalog::edit');
    }

    /**
     * Обновление возможных значений в админке
     * @param Request $request
     * @param Product $product
     * @return string
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->only(['is_new', 'is_sale', 'is_offer']));
        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
