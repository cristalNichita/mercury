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

class SpecialProductController extends BaseController
{

    public function index(Request $request, string $special)
    {
        if (!is_null($special)) {
            $fields_special = ['is_new', 'is_offer', 'is_sale'];
            abort_if(array_search($special, $fields_special) === false, 404, 'Товары не найдены');
        }

        $products = Product::with('brand')
            ->filter($request->get('filter', []))
            ->sort($request->get('sort'))
            ->when($special, function($query, $status) use ($special){
                return $query->where($special, 1);
            })
            ->paginate($this->per_page);

        return Inertia::render('Catalog/SpecialProduct/SpecialProductIndex', [
            'filter' => $request->all(),
            'products' => $products,
            'special_field' => $special
        ]);
    }

    /**
     * Обновление возможных значений в админке
     * @param Request $request
     * @param Product $product
     * @return string
     */
    public function update(Request $request, string $special_field, Product $product)
    {
        $product->update([$special_field => !$product->$special_field]);
        return redirect()->back();
    }
}
