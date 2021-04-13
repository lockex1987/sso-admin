<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;

Route::group(['prefix' => '/country'], function () {
    Route::get('/search', [CountryController::class, 'search']);
    Route::post('/store', [CountryController::class, 'store']);
    Route::delete('/destroy/{country}', [CountryController::class, 'destroy']);
    Route::delete('/destroy-multiple', [CountryController::class, 'destroyMultiple']);
});
