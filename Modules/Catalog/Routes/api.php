<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\Api\ApiRecommendedProductController;

Route::prefix('catalog')->name('api.catalog.')->group(function () {
    Route::get('products/{special?}', 'Api\ApiProductsController')->name('products'); // список продуктов с пагинацией
    Route::get('product/{product}', 'Api\ApiProductController')->name('product'); // вся информация по товару (id или slug)

    Route::get('recommended-products/{product?}', [ApiRecommendedProductController::class, 'index'])->name('recommended-products');

    Route::get('categories', 'Api\ApiCategoriesController')->name('categories'); // возвратить дерево всех категорий (без пагинации)
    Route::get('category/{category}', 'Api\ApiCategoryController')->name('category'); // category/{category} - возвратить полную информацию о категории (id или slug)

    // brands - возвратить всех брендов (без пагинации)
});
