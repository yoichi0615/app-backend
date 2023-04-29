<?php

use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AuthController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/income', [IncomeController::class, 'store'])->name('store');
Route::get('/income', [IncomeController::class, 'getMonthlyData'])->name('get');
Route::get('/total_income', [IncomeController::class, 'getDailyTotalAmount'])->name('get_total_income');
Route::get('/daily_amount', [IncomeController::class, 'getDailyAmount'])->name('get_daily_amount');
