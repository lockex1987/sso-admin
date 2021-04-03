<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/system-log'], function () {
    Route::get('/search', 'SystemLogController@search');
    Route::delete('/destroy', 'SystemLogController@destroy');
});
