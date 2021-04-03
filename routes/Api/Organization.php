<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/organization'], function () {
    Route::get('/search', 'OrganizationController@search');
    Route::get('/all', 'OrganizationController@getAll');
    Route::post('/store', 'OrganizationController@store');
    Route::delete('/destroy', 'OrganizationController@destroy');
});
