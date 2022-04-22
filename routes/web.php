<?php

use App\Http\Controllers\LoginController;
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

Route::get('/hi/{name?}', function($name = 'World') {
    return "Hello, $name";
});

Route::get('/bills/{bill}', function(Bill $bill){
    return $bill->makeVisible('contacts')->toJson();
});

Route::get('/signin', function(){
    return view('signin');
})->name('login');

Route::get('/users', function(){
    return User::all();
});

Route::post('/signin', [LoginController::class, 'authenticate']);

Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function(){
    Route::get('/', function() {
        return 'Welcom to admin panel';
    });

    Route::get('/users', function(){
        return User::all();
    });
    
    Route::get('/bills', function(){
        $bills = Bill::with('user')->simplePaginate(10);
        return view('admin.bills')->with('bills', $bills);
    });
});
