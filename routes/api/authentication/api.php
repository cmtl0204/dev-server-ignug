<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Users
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Authentication\AuthController@login');
    // Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'Authentication\AuthController@logout');
        Route::put('password', 'Authentication\AuthController@changePassword');
        Route::put('users', 'Authentication\AuthController@updateUser');
        Route::get('users/{id}', 'Authentication\AuthController@showUser');
        Route::get('users', 'Authentication\AuthController@indexUser');
        Route::post('users', 'Authentication\AuthController@createUser');
        Route::post('users', 'Authentication\AuthController@createUser');
        Route::delete('users/{id}', 'Authentication\AuthController@destroyUser');
        Route::post('users/avatar', 'Authentication\AuthController@uploadAvatarUri');
    // });
});
