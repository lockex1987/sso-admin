<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/app'], function () {
    Route::get('/search', 'AppController@search');
    Route::get('/all', 'AppController@getAll');
    Route::post('/store', 'AppController@store');
    Route::delete('/destroy', 'AppController@destroy');
});
