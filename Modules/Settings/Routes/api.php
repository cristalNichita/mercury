<?php

Route::get('/settings', 'Api\ApiSettingsController');
Route::get('/dict/{dict}', 'Api\ApiGlobalDirectoryController');
