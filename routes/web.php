<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Models\Bill;
use App\Models\User;
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
    return "Hi there";
});

Route::get('/bills/{bill}', function(Bill $bill){
    return $bill->makeVisible('contacts')->toJson();
});

Route::get('/signup', [SignupController::class, 'index']);

Route::post('/signup', [SignupController::class, 'create']);

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'create']);

Route::get('/users', function(){
    return User::all();
});


Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function(){
    Route::get('/', function() {
        return 'Welcome to admin panel';
    });

    Route::get('/users', function(){
        return User::all();
    });

    Route::get('/bills', function(){
        $bills = Bill::with('user')->simplePaginate(10);
        return view('admin.bills')->with('bills', $bills);
    });
});
