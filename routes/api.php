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

Route::post('/convert-user', [\App\Http\Controllers\ConvertUserController::class, 'getUid']);
Route::post('/query-play', [\App\Http\Controllers\QueryPlayController::class, 'queryPlay']);
Route::post('/create-card', [\App\Http\Controllers\CardController::class, 'store']);
Route::get('/test-api', [\App\Http\Controllers\QueryPlayController::class, 'test']);
