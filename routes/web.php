<?php

use App\Models\Ignug\State;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

});
Route::get('password_generate', function () {
    return \Illuminate\Support\Facades\Hash::make('123');
});

