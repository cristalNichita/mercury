<?php
Route::get('/', function () {
    return [
        'api' => true,
        'doc' => env('APP_URL') . '/api/documentation',
        'user'=> auth('sanctum')->user()
    ];
});
