<?php

use Illuminate\Support\Facades\Route;
use \Modules\User\Http\Controllers\Api\ApiDocumentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('user')->name('api.user.')->group(function () {
    Route::post('login', 'Api\ApiLoginController')->name('login');
    Route::post('register', 'Api\ApiRegisterController')->name('register');

    Route::post('login-confirm', 'Api\ApiUserController@loginConfirm')->name('login.confirm');

    Route::post('password/email', 'Api\ApiForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'Api\ApiForgotPasswordController@resetPassword')->name('password.reset');

    Route::prefix('settings')->middleware('auth:sanctum')->name('settings.')->group(function () {
        Route::post('update', 'Api\ApiUserSettingsController@update')->name('update');
    });

    Route::prefix('document')->middleware(['auth:sanctum'])->name('document.')->group(function () {
        Route::post('/request', [ApiDocumentController::class, 'request'])->name('document.request');
        Route::get('/show/{document}', [ApiDocumentController::class, 'show'])->name('document.show');
        Route::get('/{type}', [ApiDocumentController::class, 'index'])->name('document.index');
    });
});

Route::prefix('user')->name('api.user.')->middleware(['auth:sanctum'])->group(function () {

    Route::post('confirm', 'Api\ApiUserController@confirm')->name('confirm');

    Route::post('profile', 'Api\ApiUserController@profile')->name('profile');
    Route::post('password', 'Api\ApiUserController@password')->name('password');

    Route::get('holdings', 'Api\ApiHoldingController')->name('holdings');

    Route::resource('address', 'Api\ApiUserAddressController')
        ->except(['create', 'show', 'edit']);

    Route::resource('contacts', 'Api\ApiUserContactController')
        ->except(['create', 'show', 'edit']);

    Route::resource('company', 'Api\ApiUserCompanyController')
        ->except(['create', 'show', 'edit']);

    Route::resource('company/{company}/bank-requisites', 'Api\ApiUserCompanyBankRequisitesController')
        ->except(['create', 'edit']);
});
