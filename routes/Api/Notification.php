<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/notification'], function () {
    Route::get('/search', 'NotificationController@search');
    Route::get('/unread-number', 'NotificationController@getUnreadNumber');
    Route::post('/mark-read', 'NotificationController@markRead');
    Route::post('/publish', 'NotificationController@testPublish');
});
