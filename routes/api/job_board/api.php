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
Route::get('professionals', function(){

    $users = \App\User::get();
    return response()->json([
        'data' => [
            'type' => 'users',
            'attributes' => $users
        ]
    ], 200);
});

