<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ApiResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', User::ROLE_USER)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return ApiResponse::success(['users' => $users]);
    }
}
