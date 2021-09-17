<?php

// Главная страница модуля
Route::get('/', function () {
    return redirect(route('orders.web.index'));
})->name('orders');

Route::prefix('settings/orders')->middleware(['auth:sanctum', 'role:admin,manager,content'])->group(function () {

    // Настройки оплаты
    Route::get('payment-settings', 'OrderController@paymentSettings')->name('orders.payment-settings');
    Route::put('payment-settings', 'OrderController@paymentSettingsSave')->name('orders.payment-settings.save');

//
//    Route::get('/view/{order}', 'OrderController@order')->name('orders.view');
//
//    Route::get('/{type}', 'OrderController@orders')->name('orders.type');
//});
//
//
//Route::prefix('deliveries')->middleware(['auth:sanctum', 'role:admin,manager,content'])->group(function() {
//    Route::get('/', 'DeliveryController@index')->name('deliveries');
//    Route::get('/{delivery}', 'DeliveryController@show')->name('delivery.view');
//    Route::put('/{delivery}', 'DeliveryController@update')->name('delivery.update');
//
});

// Заказы в админке
Route::resource('orders', 'OrderController')
    ->middleware(['auth:sanctum', 'role:admin,manager,content'])
    ->names([
        'index' => 'orders.web.index',
        'create' => 'orders.web.create',
        'store' => 'orders.web.store',
        'show' => 'orders.web.show',
        'edit' => 'orders.web.edit',
        'update' => 'orders.web.update',
        'destroy' => 'orders.web.destroy',
    ]);

//Корзины пользователей
Route::prefix('settings/carts')->middleware(['auth:sanctum', 'role:admin,manager,content'])->group(function () {
    // Настройки корзины
    Route::get('settings', 'CartController@cartSettings')->name('carts.settings');
    Route::put('settings', 'CartController@cartSettingsSave')->name('carts.settings.save');
});
