<?php

use Illuminate\Http\Request;
use Modules\Complaint\Http\Controllers\Api\ApiComplaintController;


Route::prefix('complaint')->middleware(['auth:sanctum'])->name('api.complaint.')->group(function() {

    Route::get('', [ApiComplaintController::class, 'index'])->name('complaint.index');
    Route::get('/show/{complaint}', [ApiComplaintController::class, 'show'])->name('complaint.show');
    Route::post('/add', [ApiComplaintController::class, 'add'])->name('complaint.add');
    Route::post('/cancel/{complaint}', [ApiComplaintController::class, 'cancel'])->name('complaint.cancel');
});
