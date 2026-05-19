<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);

    Route::middleware('admin')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::get('/users', [UserController::class, 'index']);
    });
});
