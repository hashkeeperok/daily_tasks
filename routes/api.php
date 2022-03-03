<?php

use App\Http\Controllers\API\DailyPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/daily_plan', [DailyPlanController::class, 'getOrCreate']);

Route::middleware('auth:sanctum')->prefix('/daily_plan/{daily_plan_id}/task/{task_id}')->group(function() {
    Route::put('complete', [DailyPlanController::class, 'taskComplete']);
    Route::put('change', [DailyPlanController::class, 'taskChange']);
});
