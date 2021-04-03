<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/province'], function () {
    Route::get('/search', 'ProvinceController@search');
    Route::post('/store', 'ProvinceController@store');
    Route::delete('/destroy/{province}', 'ProvinceController@destroy');
    Route::delete('/destroy-multiple', 'ProvinceController@destroyMultiple');
});
