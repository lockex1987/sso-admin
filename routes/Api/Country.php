<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/country'], function () {
    Route::get('/search', 'CountryController@search');
    Route::post('/store', 'CountryController@store');
    Route::delete('/destroy/{country}', 'CountryController@destroy');
    Route::delete('/destroy-multiple', 'CountryController@destroyMultiple');
});
