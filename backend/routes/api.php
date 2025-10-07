<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\UserAuthorityController;
use App\Http\Controllers\AclController;

// Auth
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

// Groups
Route::apiResource('groups', GroupController::class);

// Organizations
Route::apiResource('organizations', OrganizationController::class);

// Province
Route::apiResource('province', ProvinceController::class);

// City
Route::apiResource('city', CityController::class);

// Licenses
Route::apiResource('licenses', LicenseController::class);

// Rules
Route::apiResource('rules', RuleController::class);

// User-Authorty
Route::apiResource('user-authorities', UserAuthorityController::class);

// Acls
Route::apiResource('acls', AclController::class);