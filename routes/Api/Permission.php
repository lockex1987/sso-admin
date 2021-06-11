<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/permission'], function () {
    Route::get('/search', [PermissionController::class, 'search']);
    Route::get('/all', [PermissionController::class, 'getAll']);
    Route::post('/store', [PermissionController::class, 'store']);
    Route::delete('/destroy', [PermissionController::class, 'destroy']);
});
