<?php

use \Illuminate\Support\Facades\Route;
use Modules\Site\Http\Controllers\Api\ApiContactController;
use Modules\Site\Http\Controllers\Api\ApiInfoBlockController;
use Modules\Site\Http\Controllers\Api\ApiInfoPageController;
use Modules\Site\Http\Controllers\Api\ApiNewsController;
use Modules\Site\Http\Controllers\Api\ApiProjectController;
use Modules\Site\Http\Controllers\Api\ApiServiceController;
use Modules\Site\Http\Controllers\Api\ApiSliderController;

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

Route::prefix('site')->name('api.site.')->group(function() {

    Route::get('sliders/{type?}', [ApiSliderController::class, 'index'])->name('sliders');
    Route::get('info-blocks', [ApiInfoBlockController::class, 'index'])->name('info-blocks');
    Route::get('contacts', [ApiContactController::class, 'index'])->name('contacts');

    Route::get('projects', [ApiProjectController::class, 'index'])->name('projects');
    Route::get('projects/{project}', [ApiProjectController::class, 'show'])->name('projects.show');

    Route::get('services', [ApiServiceController::class, 'index'])->name('services');
    Route::get('services/{service}', [ApiServiceController::class, 'show'])->name('services.show');

    Route::get('news', [ApiNewsController::class, 'index'])->name('news');
    Route::get('news/{news}', [ApiNewsController::class, 'show'])->name('news.show');

    Route::get('info-pages', [ApiInfoPageController::class, 'index'])->name('info-pages');
    Route::get('info-pages/{info_page}', [ApiInfoPageController::class, 'show'])->name('info-pages.show');

});
