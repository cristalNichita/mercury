<?php
/* Routes модуля сайт */

use \Illuminate\Support\Facades\Route;
use Modules\Site\Http\Controllers\InfoBlockController;
use Modules\Site\Http\Controllers\PageController;
use Modules\Site\Http\Controllers\ProjectController;
use Modules\Site\Http\Controllers\SiteController;
use Modules\Site\Http\Controllers\SliderController;


Route::prefix('site')->middleware(['auth:sanctum', 'role:admin,content'])->group(function() {
    Route::get('/', [SiteController::class, 'index'])->name('site');

    Route::name('site.')->group(function () {
        Route::resource('main-sliders', 'MainSliderController')
            ->parameters(['main-sliders' => 'slider'])
            ->names(['index' => 'main-sliders'])
            ->except(['show']);

        Route::resource('about-company-sliders', 'AboutCompanySliderController')
            ->parameters(['about-company-sliders' => 'slider'])
            ->names(['index' => 'about-company-sliders'])
            ->except(['show']);

        Route::resource('info-blocks', 'InfoBlockController')
            ->names(['index' => 'info-blocks'])
            ->except(['show']);

        Route::resource('contacts', 'ContactController')
            ->names(['index' => 'contacts'])
            ->except(['show']);

        Route::get('info-blocks/{info_block}/remove-image', [InfoBlockController::class, 'removeImage'])
            ->name('info-blocks.remove-image');

        Route::resource('projects', 'ProjectController')
            ->parameters(['projects' => 'page'])
            ->names(['index' => 'projects'])
            ->except(['show']);

        Route::resource('services', 'ServiceController')
            ->parameters(['services' => 'page'])
            ->names(['index' => 'services'])
            ->except(['show']);

        Route::resource('news', 'NewsController')
            ->parameters(['news' => 'page'])
            ->names(['index' => 'news'])
            ->except(['show']);

        Route::resource('info', 'InfoBlockPageController')
            ->parameters(['info' => 'page'])
            ->names(['index' => 'info-pages'])
            ->except(['show', 'create', 'index', 'store']);
    });

    Route::post('/pages/{page}/gallery/{id}/remove', [PageController::class, 'removeGalleryImage'])->name('site.pages.remove-gallery-image');
    Route::get('/pages/{page}/', [PageController::class, 'toggleActive'])->name('site.pages.toggle-active');
    Route::get('/info-blocks/{info_block}/', [InfoBlockController::class, 'toggleInMain'])->name('site.info-blocks.toggle-main');
});
