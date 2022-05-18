<?php

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
    return view('welcome');
});

Route::get('callback', function (){
    return 'success';
})->name('callback');

Route::get('error', function (){
    return 'error';
})->name('error');
Route::get('paid',  [\App\Http\Controllers\PaymentController::class,'paid']);

