<?php

use App\Http\Controllers\IncomeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/income', [IncomeController::class, 'store']);
Route::get('/income', [IncomeController::class, 'getMonthlyData']);
Route::get('/total_income', [IncomeController::class, 'getDailyTotalAmount']);
Route::get('/daily_amount', [IncomeController::class, 'getDailyAmount']);
