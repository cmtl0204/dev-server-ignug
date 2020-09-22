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

// Route::get('evaluaciones','TeacherEval\EvaluacionController@calificar');
//Route::get('evaluaciones','TeacherEval\EvaluacionController@index');
//Route::post('evaluaciones','TeacherEval\EvaluacionController@store');
//Route::put('evaluaciones','TeacherEval\EvaluacionController@update');
//Route::delete('evaluaciones','TeacherEval\EvaluacionController@destroy');

//Route::group(['prefix' => 'evaluaciones'], function () {
//    Route::get('calificar','TeacherEval\EvaluacionController@index');
//    Route::get('evaluar_pares','TeacherEval\EvaluacionController@index');
//});
//Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('evaluaciones', 'TeacherEval\EvaluacionController');
//});


