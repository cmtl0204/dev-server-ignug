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
    Route::group(['prefix' => 'catalogues'], function () {
        //Route::group(['middleware' => 'auth:api'], function () {
        Route::get('', 'Ignug\CatalogueController@filter');
        Route::get('index', 'Ignug\CatalogueController@index');
        //});
    });
