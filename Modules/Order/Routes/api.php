<?php

use \Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Api\ApiCartController;
use Modules\Order\Http\Controllers\Api\ApiOrderController;


// Заказы - защита middleware в самом контроллере
Route::resource('orders', 'Api\ApiOrderController');




//Route::prefix('orders')->middleware(['auth:sanctum'])->name('api.orders.')->group(function() {
//
//    Route::delete('{order}', [ApiOrderController::class, 'cancel'])->name('order.cancel');
//
//    Route::get('', [ApiOrderController::class, 'index'])->name('order.index');
//    Route::get('/show/{order}', [ApiOrderController::class, 'show'])->name('order.show');
//    Route::post('{delivery:code}/create', [ApiOrderController::class, 'createOld']);
//});


/** need refactor */

// Оформление заказа
Route::prefix('order')->name('api.order.')->group(function() {
    // any - временно для отладки
    Route::any('create', [ApiOrderController::class, 'create'])->name('create');
    Route::delete('cancel/{order}', [ApiOrderController::class, 'cancel'])->name('cancel');
});

Route::prefix('order')->name('api.order.')->group(function() {
    Route::prefix('cart')->group(function() {
        Route::get('', [ApiCartController::class, 'index'])->name('cart.index');
        Route::post('add', [ApiCartController::class, 'add'])->name('cart.add');
        Route::post('change', [ApiCartController::class, 'change'])->name('cart.change');
        Route::post('remove', [ApiCartController::class, 'remove'])->name('cart.remove');
        Route::post('clear', [ApiCartController::class, 'clear'])->name('cart.clear');
    });
    Route::post('payment/check', 'Api\ApiOrderPaymentController')->name('payment.result');
});
//
//
//Route::prefix('delivery')->name('api.delivery.')->group(function() {
//    Route::get('all', [\Modules\Order\Http\Controllers\Api\ApiDeliveryController::class, 'index'])->name('all');
//
//    Route::prefix('{delivery:code}')->group(function() {
//        Route::get('regions', [\Modules\Order\Http\Controllers\Api\ApiDeliveryController::class, 'getRegions'])->name('regions');
//        Route::get('cities', [\Modules\Order\Http\Controllers\Api\ApiDeliveryController::class, 'getCities'])->name('cities');
//        Route::get('pvz', [\Modules\Order\Http\Controllers\Api\ApiDeliveryController::class, 'getPVZList'])->name('pvz');
//        Route::post('calculate', [\Modules\Order\Http\Controllers\Api\ApiDeliveryController::class, 'calculate'])->name('calculate');
//    });
//
//});
