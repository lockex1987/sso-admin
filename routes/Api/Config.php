<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/config'], function () {
    Route::get('/search', 'ConfigController@search');
    Route::post('/store', 'ConfigController@store');
    Route::delete('/destroy/{config}', 'ConfigController@destroy');
});
