<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/log-file'], function () {
    Route::get('/list', 'LogFileController@list');
    Route::get('/download', 'LogFileController@download');
});
