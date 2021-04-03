<?php

use Illuminate\Support\Facades\Route;


Route::post('/logout', 'LoginController@logout');
Route::post('/login-callback', 'LoginController@loginCallback');
Route::get('/me', 'LoginController@getUserInfo');

Route::group(['middleware' => ['checkLogin']], function () {
    require __DIR__ . '/Api/User.php';
    require __DIR__ . '/Api/App.php';
    require __DIR__ . '/Api/Permission.php';
    require __DIR__ . '/Api/Role.php';
    require __DIR__ . '/Api/Dashboard.php';
    require __DIR__ . '/Api/Notification.php';
    require __DIR__ . '/Api/Organization.php';
    require __DIR__ . '/Api/Content.php';
    require __DIR__ . '/Api/Config.php';
    require __DIR__ . '/Api/Country.php';
    require __DIR__ . '/Api/Category.php';
    require __DIR__ . '/Api/SystemLog.php';
    require __DIR__ . '/Api/LogFile.php';
    require __DIR__ . '/Api/Province.php';
    require __DIR__ . '/Api/District.php';
    require __DIR__ . '/Api/Commune.php';
    require __DIR__ . '/Api/Slide.php';
});
