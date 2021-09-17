<?php

namespace Modules\Catalog\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Modules\Catalog\Entities\Product;

/**
 * API для получения информации о конкретном товаре
 *
 * должен возвращать полную информацию со всеми связанными сущностями
 * достаточную для отображение страницы товара на фронте
 *
 * @package Modules\Catalog\Http\Controllers\Api
 */
class ApiProductController extends BaseController
{
    public function __invoke(Product $product)
    {
        return $product->load(['categories', 'recommendation.recommendedProduct'])->append(['image', 'gallery', 'params']);
    }
}
