<?php

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
   // Route::group(['middleware' => 'auth:api'], function () {
        Route::get('', 'Ignug\CatalogueController@filter');
  //  });
});
// http://127.0.0.1:8000/api/attendance/wokdays/all
Route::group(['prefix' => 'workdays'], function () {
    //Route::group(['middleware' => 'auth:api'], function () {
        Route::get('all', 'Attendance\WorkdayController@all');
        Route::get('current_day', 'Attendance\WorkdayController@getCurrenDate');
        Route::post('start_day', 'Attendance\WorkdayController@startDay');
        Route::put('', 'Attendance\WorkdayController@update');
        Route::put('end_day', 'Attendance\WorkdayController@endDay');
        Route::delete('{id}', 'Attendance\WorkdayController@destroy');
    //});
});

Route::group(['prefix' => 'tasks'], function () {
  //  Route::group(['middleware' => 'auth:api'], function () {
        Route::get('all', 'Attendance\TaskController@all');
        Route::get('catalogues', 'Attendance\TaskController@allCatalogues');
        Route::get('current_day', 'Attendance\TaskController@getCurrenDate');
        Route::get('history', 'Attendance\TaskController@getHistory');
        Route::post('', 'Attendance\TaskController@store');
        Route::put('', 'Attendance\TaskController@update');
        Route::delete('{id}', 'Attendance\TaskController@destroy');
  //  });
});

Route::group(['prefix' => 'attendances'], function () {
   // Route::group(['middleware' => 'auth:api'], function () {
        Route::get('summary', 'Attendance\AttendanceController@summary');
        Route::get('detail', 'Attendance\AttendanceController@detail');
        Route::get('{id}', 'Attendance\AttendanceController@show');
        Route::post('', 'Attendance\AttendanceController@store');
        Route::put('', 'Attendance\AttendanceController@update');
        Route::delete('{id}', 'Attendance\AttendanceController@destroy');
   // });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('institutions', 'Ignug\InstitutionController');
});


