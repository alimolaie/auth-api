<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\AuthApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthApiController::class,'register']);
Route::post('/confirm-mobile', [AuthApiController::class,'confirmMobile']);
Route::post('/login', [AuthApiController::class,'login']);
