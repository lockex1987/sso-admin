<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/content'], function () {
    Route::get('/search', 'ContentController@search');
    Route::get('/{content}', 'ContentController@getOne');
    Route::post('/store', 'ContentController@store');
    Route::delete('/destroy/{content}', 'ContentController@destroy');
    Route::post('/change-status/{content}', 'ContentController@changeStatus');
    Route::delete('/delete-image', 'ContentController@deleteImage');
});
