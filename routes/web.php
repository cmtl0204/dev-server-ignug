<?php

use App\Models\Ignug\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::put('/', function (Request $request) {
    $user = User::findOrFail(5);
    $attendances = $user->attendances()
        ->with(['workdays' => function ($query) {
            $query->with('state');
        }])->where('state_id', '<>', 3)->get();

    return response()->json([
        'data' => [
            'type' => 'attendances',
            'attributes' => $attendances
        ]
    ], 200);
});
Route::get('password_generate', function () {
    return \Illuminate\Support\Facades\Hash::make('123');
});

