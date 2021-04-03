<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/slide'], function () {
    Route::get('/search', 'SlideController@search');
    Route::post('/store', 'SlideController@store');
    Route::delete('/destroy/{slide}', 'SlideController@destroy');
    Route::post('/update-is-active', 'SlideController@updateIsActive');
});
