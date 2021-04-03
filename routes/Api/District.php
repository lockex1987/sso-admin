<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/district'], function () {
    Route::get('/search', 'DistrictController@search');
    Route::post('/store', 'DistrictController@store');
    Route::delete('/destroy/{district}', 'DistrictController@destroy');
    Route::delete('/destroy-multiple', 'DistrictController@destroyMultiple');
});
