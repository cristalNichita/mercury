<?php

namespace Modules\Catalog\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Modules\Catalog\Entities\Category;

/**
 * API для получения списка категорий в виде дерева
 *
 * @package Modules\Catalog\Http\Controllers\Api
 */
class ApiCategoriesController extends BaseController
{
    public function __invoke()
    {
        return Category::all()->toTree();
    }
}
