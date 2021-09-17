<?php

use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::prefix('users')->middleware(['auth:sanctum', 'role:admin'])->group(function() {
    Route::get('', 'UserController@index')->name('users');

    Route::get('/edit/{user}', 'UserController@edit')->name('users.edit');
    Route::delete('/delete/{user}', 'UserController@destroy')->name('users.delete');

    Route::get('/create', 'UserController@create')->name('users.create');
    Route::post('/create', 'UserController@store')->name('users.store');

    Route::put('/update/{user}', 'UserController@update')->name('users.update');

    Route::prefix('companies')->group(function() {
        Route::get('/company-dadata', 'CompanyController@companyDadata')->name('users.companyDadata');
    });

    Route::prefix('documents')->group(function() {
        Route::get('/', 'DocumentRequestController@index')->name('users.documents');
        Route::get('/{status}', 'DocumentRequestController@documents')->name('users.documents.status');
        Route::get('/show/{document}', 'DocumentRequestController@show')->name('users.documents.show');
        Route::get('/edit/{document}', 'DocumentRequestController@show')->name('users.documents.edit');
        Route::put('/update/{document}', 'DocumentRequestController@update')->name('users.documents.update');
    });

    Route::prefix('contacts')->group(function() {
        Route::post('/{contact}/invite', 'ContactController@inviteUser')->name('users.contacts.invite');
    });

    Route::get('company/{company}/bank-requisites/{bank_requisite}/close', 'CompanyBankRequisitesController@toggleClosed')->name('users.company.bank-requisites.closed');
    Route::get('company/{company}/bank-requisites/{bank_requisite}/default', 'CompanyBankRequisitesController@toggleDefault')->name('users.company.bank-requisites.default');

    Route::name('users.')->group(function () {
        Route::resource('holdings', 'HoldingController');
        Route::resource('contacts', 'ContactController');
        Route::resource('company', 'CompanyController');
	    Route::resource('company.bank-requisites', 'CompanyBankRequisitesController');
    });

    Route::prefix('holdings')->group(function() {
        Route::post('/{holding}/update-owner', 'HoldingController@updateOwner')->name('users.holdings.updateOwner');
    });

    Route::prefix('holdings')->group(function() {
        Route::post('/{holding}/add-company', 'HoldingController@addCompany')->name('users.holdings.addCompany');
    });
});
