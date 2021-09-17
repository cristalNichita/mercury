<?php
/* Routes модуля каталог */

use \Illuminate\Support\Facades\Route;
use Modules\Catalog\Http\Controllers\BrandController;
use Modules\Catalog\Http\Controllers\ParameterController;
use Modules\Catalog\Http\Controllers\CatalogController;
use Modules\Catalog\Http\Controllers\CategoryController;
use Modules\Catalog\Http\Controllers\ProductController;
use Modules\Catalog\Http\Controllers\RecommendedProductController;
use Modules\Catalog\Http\Controllers\SpecialProductController;

Route::prefix('catalog')->middleware(['auth:sanctum', 'role:admin,manager,content'])->group(function () {
    Route::get('', [CatalogController::class, 'index'])->name('catalog');

    Route::get('products', [ProductController::class, 'index'])->name('catalog.products');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('catalog.product');
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('catalog.product.update');

    Route::name('catalog.')->group(function () {
        Route::get('recommended-products/{parent_product?}', [RecommendedProductController::class, 'index'])
            ->name('recommended-products');
        Route::post('recommended-products/{parent_product?}', [RecommendedProductController::class, 'store'])
            ->name('recommended-products.store');
        Route::delete('recommended-products/{product}', [RecommendedProductController::class, 'destroy'])
            ->name('recommended-products.destroy');

        Route::get('special-products/{special_field}', [SpecialProductController::class, 'index'])
            ->name('special-products.index');
        Route::patch('special-products/{special_field}/{product}', [SpecialProductController::class, 'update'])
            ->name('special-products.update');
    });

    Route::get('parameters', [ParameterController::class, 'index'])->name('catalog.parameters');
    Route::get('brands', [BrandController::class, 'index'])->name('catalog.brands');
    Route::get('categories', [CategoryController::class, 'index'])->name('catalog.categories');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('catalog.category');
    Route::post('categories/{category}/updateImage', [CategoryController::class, 'updateImage'])->name('catalog.category.updateImage');
    Route::post('categories/{category}/removeImages', [CategoryController::class, 'removeImages'])->name('catalog.category.removeImages');
});
