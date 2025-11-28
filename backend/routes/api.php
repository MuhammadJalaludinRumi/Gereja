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
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\EconomyHistoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ForgotPasswordController;

//Route Auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/news/login', [NewsController::class, 'newsForLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/users/send-otp', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/users/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/users/reset-password', [ForgotPasswordController::class, 'resetPassword']);

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

    //Member
    Route::apiResource('members', MemberController::class);

    //KK
    Route::get("/members/by-kk/{kk}", [MemberController::class, "byKK"]);

    //Marriage
    Route::apiResource('marriages', MarriageController::class);

    //Route Educations
    Route::apiResource('educations', EducationController::class);

    //Route Occupations
    Route::apiResource('occupations', OccupationController::class);

    //Route Economy
    Route::apiResource('economies', EconomyController::class);

    Route::get('/economy/{id}/history', [EconomyHistoryController::class, 'index']);
    Route::post('/economy/{id}/history', [EconomyHistoryController::class, 'index']);
});
