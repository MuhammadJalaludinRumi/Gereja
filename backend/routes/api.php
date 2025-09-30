<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
    // Route login dan logout + Auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    try {
        return response()->json([
            'user' => $request->user(),
            'auth_check' => Auth::check()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }

    Route::post('/logout', [AuthController::class, 'logout']);
});

    //Route groups
Route::apiResource('groups', GroupController::class);
