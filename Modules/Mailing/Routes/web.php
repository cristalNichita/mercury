<?php

use Illuminate\Support\Facades\Route;

Route::prefix('settings/mailing')->middleware(['auth:sanctum', 'role:admin,manager,content'])->group(function() {
    Route::resource('events', 'MailingEventController')->middleware(['auth:sanctum', 'role:admin,manager,content']);
    Route::resource('events.statuses', 'MailingEventStatusController')->middleware(['auth:sanctum', 'role:admin,manager,content']);
});

Route::resource('settings/mailing', 'MailingController')
    ->middleware(['auth:sanctum', 'role:admin,manager,content'])
    ->names([
        'index' => 'mailing.index',
        'create' => 'mailing.create',
        'edit' => 'mailing.edit',
        'update' => 'mailing.update',
        'destroy' => 'mailing.destroy'
    ]);
