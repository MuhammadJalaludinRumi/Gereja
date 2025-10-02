<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrganizationController;

// ----------------- AUTH -----------------
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    try {
        return response()->json([
            'user' => $request->user(),
            'auth_check' => \Illuminate\Support\Facades\Auth::check()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});

// ----------------- GROUPS -----------------
Route::apiResource('groups', GroupController::class);

// ----------------- ORGANIZATION -----------------
Route::apiResource('organizations', OrganizationController::class);
