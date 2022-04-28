<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
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

Route::post('/payments/pay', [PaymentController::class, 'pay'])->name('pay');
Route::get('/payments/approval', [PaymentController::class, 'approval'])->name('approval');
Route::get('/payments/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/transactions', [HomeController::class, 'list'])->name('admin');
Route::get('/ticket/{platform}/{id}', [PaymentController::class, 'search'])->name('ticket');
