<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/dashboard'], function () {
    Route::get('/stats', 'DashboardController@getStats');
});
