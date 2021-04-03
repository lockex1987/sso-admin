<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/commune'], function () {
    Route::get('/search', 'CommuneController@search');
    Route::post('/store', 'CommuneController@store');
    Route::delete('/destroy/{commune}', 'CommuneController@destroy');
    Route::delete('/destroy-multiple', 'CommuneController@destroyMultiple');
});
