<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/permission'], function () {
    Route::get('/search', 'PermissionController@search');
    Route::get('/all', 'PermissionController@getAll');
    Route::post('/store', 'PermissionController@store');
    Route::delete('/destroy', 'PermissionController@destroy');
});
