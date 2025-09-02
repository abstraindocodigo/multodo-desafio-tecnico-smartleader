<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\CompanyController;

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

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

Route::prefix('password')->group(function () {
    Route::post('forgot', [PasswordResetController::class, 'sendResetLink']);
    Route::post('reset', [PasswordResetController::class, 'resetPassword']);
    Route::post('validate-token', [PasswordResetController::class, 'validateToken']);
});

Route::prefix('email')->group(function () {
    Route::get('verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
    Route::post('resend', [EmailVerificationController::class, 'resend'])
        ->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::prefix('export')->group(function () {
        Route::post('tasks', [ExportController::class, 'exportTasks']);
        Route::get('download/{filename}', [ExportController::class, 'downloadExport'])->name('api.export.download');
    });

    // Company routes
    Route::prefix('company')->group(function () {
        Route::get('users', [CompanyController::class, 'getCompanyUsers']);

        // Admin only routes
        Route::middleware('company.admin')->group(function () {
            Route::get('stats', [CompanyController::class, 'getCompanyStats']);
            Route::get('users/{userId}/tasks', [CompanyController::class, 'getUserTasks']);
            Route::get('tasks', [CompanyController::class, 'getAllUsersTasks']);
        });
    });
});

Route::get('companies', [CompanyController::class, 'getCompanies']);
