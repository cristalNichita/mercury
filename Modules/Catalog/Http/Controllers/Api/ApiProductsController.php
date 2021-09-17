<?php

namespace Modules\Catalog\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Catalog\Entities\Product;

/**
 * API для получения списка товаров
 *
 * должен содержать:
 *  - фильтрацию по request
 *  - сортировку по request
 *
 * @package Modules\Catalog\Http\Controllers\Api
 */
class ApiProductsController extends BaseController
{
    public function __invoke(Request $request, $special = null)
    {
        if (!is_null($special)) {
            $fields_special = ['is_new', 'is_offer', 'is_sale'];
            abort_if(array_search($special, $fields_special) === false, 404, 'Товары не найдены');
        }

        $products = Product::with(['parameters_values.parameter'])
            ->filter($request->get('filter', []))
            ->filterParams($request->get('filterParams', []))
            ->sort($request->get('sort', 'id-asc'))
            ->when($special, function($query, $status) use ($special){
                return $query->where($special, 1);
            });

        return (is_null($special))
                    ? $products->paginate($this->per_page)
                    : $products->get();
    }
}
