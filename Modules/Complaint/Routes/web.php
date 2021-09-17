<?php

Route::prefix('complaints')->middleware(['auth:sanctum', 'role:admin'])->group(function() {
    Route::get('/{status?}', 'ComplaintController@index')->name('complaints');


    //Route::get('/view/{complaint}', 'ComplaintController@complaint')->name('complaints.view');
    Route::get('/show/{complaint}', 'ComplaintController@show')->name('complaints.show');
//    Route::get('/edit/{complaint}', 'ComplaintController@show')->name('complaints.edit');
    Route::put('/update/{complaint}', 'ComplaintController@update')->name('complaints.update');
});
