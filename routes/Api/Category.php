<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/category'], function () {
    Route::get('/search', 'CategoryController@search');
    Route::get('/{category}', 'CategoryController@getOne');
    
    Route::post('/store', 'CategoryController@store');
    Route::delete('/destroy', 'CategoryController@destroy');
});
