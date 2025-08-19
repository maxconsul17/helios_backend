<?php

use App\Http\Controllers\Dtr\UploadLogsController;
use App\Http\Controllers\Schedule\EmployeeScheduleController;
use App\Http\Controllers\Schedule\ScheduleShiftController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/upload-logs', UploadLogsController::class);
Route::apiResource('/employee-schedule', EmployeeScheduleController::class);
Route::apiResource('/schedule-shift', ScheduleShiftController::class);
