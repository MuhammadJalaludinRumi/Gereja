<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationLicenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\UserAuthorityController;
use App\Http\Controllers\AclController;

//Route Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    // route buat cek user & auth status
    Route::get('/me', function (Request $request) {
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

    // 🔥 route profile user
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    //Route Users
    Route::apiResource('users', UserController::class);

    //Route Roles
    Route::apiResource('roles', RoleController::class);

    //Route Groups
    Route::apiResource('groups', GroupController::class);

    //Route Organizations
    Route::apiResource('organizations', OrganizationController::class);

    //Route Province
    Route::apiResource('province', ProvinceController::class);

    //Route City
    Route::apiResource('city', CityController::class);

    //Route Organization License
    Route::apiResource('organization-licenses', OrganizationLicenseController::class);

    //Route Invoice
    Route::apiResource('invoices', InvoiceController::class);

    //Route News
    Route::apiResource('news', NewsController::class);

    // Licenses
    Route::apiResource('licenses', LicenseController::class);

    // Rules
    Route::apiResource('rules', RuleController::class);

    // User-Authorty
    Route::apiResource('user-authorities', UserAuthorityController::class);

    // Acls
    Route::apiResource('acls', AclController::class);
});
//
