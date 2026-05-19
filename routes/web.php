<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'app' => 'PMS Laravel API',
    'version' => '1.0',
    'status' => 'running',
    'message' => 'API is up. Use /api/* endpoints (not this root URL).',
    'examples' => [
        'POST /api/login' => ['email' => 'admin@pms.test', 'password' => 'password'],
        'GET /api/projects' => 'Requires Bearer token',
        'GET /api/tasks' => 'Requires Bearer token',
    ],
    'frontend' => 'http://127.0.0.1:5173',
]));
