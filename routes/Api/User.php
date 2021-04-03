<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/user'], function () {
    Route::get('/search', 'UserController@search');
    Route::post('/store', 'UserController@store');
    Route::delete('/destroy', 'UserController@destroy');

    Route::post('/update-is-active', 'UserController@updateIsActive');
    Route::post('/view-as', 'UserController@viewAs');

    Route::get('/roles', 'UserController@getRoles');
    Route::post('/roles', 'UserController@updateRoles');

    Route::get('/apps', 'UserController@getApps');
    Route::post('/apps', 'UserController@updateApps');
});
