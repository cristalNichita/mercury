<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
   return redirect()->route('catalog.products');
})->middleware(['auth:sanctum', 'role:admin,manager,content'])->name('dashboard');
