<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/role'], function () {
    Route::get('/search', 'RoleController@search');
    Route::get('/all', 'RoleController@getAll');
    Route::post('/store', 'RoleController@store');
    Route::delete('/destroy', 'RoleController@destroy');

    Route::get('/permissions', 'RoleController@getPermissions');
    Route::post('/permissions', 'RoleController@updatePermissions');
});
