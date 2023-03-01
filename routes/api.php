<?php

use App\Http\Controllers\Api\BillReportController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\TypeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;

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



Route::prefix("v1")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);

    Route::post('/signup', [AuthController::class, 'signUp']);

    Route::post('/signout', [AuthController::class, 'signOut'])
        ->middleware('auth:sanctum');

    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::patch('/', [ProfileController::class, 'update']);
        Route::delete('/', [ProfileController::class, 'destroy']);
        Route::apiResource('tokens', TokenController::class)->only(['index', 'destroy']);
    });

    Route::apiResource('bills', BillController::class)
        ->middleware('auth:sanctum');

    Route::apiResource('cities', CityController::class)
        ->except(['index', 'show'])->middleware('auth:sanctum');
    Route::apiResource('cities', CityController::class)->only(['index', 'show']);

    Route::apiResource('types', TypeController::class)
        ->except(['index', 'show'])->middleware('auth:sanctum');
    Route::apiResource('types', TypeController::class)->only(['index', 'show']);

    Route::apiResource('users', UserController::class)->except('create')
        ->middleware('auth:sanctum');

    Route::apiResource('bills.reports', BillReportController::class)->shallow()
        ->except(['update'])->middleware('auth:sanctum');
});

