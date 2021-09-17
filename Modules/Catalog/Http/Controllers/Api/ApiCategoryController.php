<?php

namespace Modules\Catalog\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;

/**
 * API для получения информации о конкретной категории
 *
 * @package Modules\Catalog\Http\Controllers\Api
 */
class ApiCategoryController extends BaseController
{
    public function __invoke(Category $category)
    {
        return $category->append('filters');
    }
}
