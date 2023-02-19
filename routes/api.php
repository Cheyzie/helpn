<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillController;
use App\Models\User;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::prefix("v1")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post('/signup', [AuthController::class, 'signUp']);
    Route::post('/signout', [AuthController::class, 'signOut'])
        ->middleware('auth:sanctum');

    Route::apiResource('/bills', BillController::class)
        ->middleware('auth:sanctum');

    Route::apiResource('/cities', CityController::class)
        ->except(['index', 'show'])->middleware('auth:sanctum');
    Route::apiResource('/cities', CityController::class)->only(['index', 'show']);

    Route::apiResource('/users', UserController::class)->except('create')
        ->middleware('auth:sanctum');

    Route::get("/me", function (Request $request){
        return User::where('id', $request->user()->id)->get()->makeVisible(['role']);
    })->middleware('auth:sanctum');
});

