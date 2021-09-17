<?php

use \Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::prefix('settings')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [SettingsController::class, 'indexModule'])->name('settings');
//    Route::get('/settings','SettingsController@index')->name('settings.settings');
    Route::put('settings','SettingsController@update')->name('settings.settings.save');
    Route::get('sms-balance', [SettingsController::class, 'smsBalance'])->name('settings.sms-balance');

    Route::name('settings.')->group(function () {
        Route::resource('directory', 'GlobalDirectoryController')
            ->names(['index' => 'directory'])
            ->except(['show']);

        Route::resource('settings','SettingsController')
            ->names(['index' => 'settings'])
            ->except(['show']);

        Route::resource('directory.item', 'GlobalDirectoryItemController')
            ->except(['index', 'show']);
    });

});
