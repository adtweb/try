<?php

use App\Http\Controllers\ApiController;
use App\Http\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Route;

Route::controller(ApiController::class)->group(function () {
    Route::get('/affiliations', 'affiliations')->name('affiliations.index');
    Route::get('/countries', 'countries')->name('countries.index');
    Route::get('current-user', 'currentUser')->name('api.current-user')
        ->middleware([EncryptCookies::class]);
});
