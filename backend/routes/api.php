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
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EconomyController;
use App\Http\Controllers\EconomyHistoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\FormulirController;
use App\Http\Controllers\AuxiliaryPersonController;
use App\Http\Controllers\KkLinkController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetImageController;
use App\Http\Controllers\AssetLoanController;
use App\Http\Controllers\AssetMaintenanceController;
use App\Http\Controllers\AssetMovementController;
use App\Http\Controllers\AssetDisposalController;
use App\Http\Controllers\AssetDocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReflectionController;

Route::apiResource('formulirs', FormulirController::class);

//Route Auth
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/news/login', [NewsController::class, 'newsForLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/user/register', [UserController::class, 'userRegister'])->name('userRegister');

// Route Auth Mobile
Route::post('/mobile/login', [AuthController::class, 'mobileLogin'])->name('mobileLogin');
Route::middleware('auth:sanctum')->post(
    '/mobile/logout',
    [AuthController::class, 'mobileLogout']
);

//
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

    // Auxiliary Persons
    Route::apiResource('auxiliary-persons', AuxiliaryPersonController::class);
    Route::get('/auxiliary-persons/by-member/{memberId}', [AuxiliaryPersonController::class, 'getByMember']);

    Route::apiResource('kk-links', KkLinkController::class);
    Route::get('/connected/{nokk}', [KkLinkController::class, 'getLinkedKKs']);
    Route::get('/family/{nokk}', [KkLinkController::class, 'getFamilyMembers']);

    Route::apiResource('assets', AssetController::class);
    Route::apiResource('asset-categories', AssetCategoryController::class);
    Route::apiResource('locations', LocationController::class);
    Route::apiResource('asset-images', AssetImageController::class);
    Route::apiResource('asset-loans', AssetLoanController::class);
    Route::apiResource('asset-maintenance', AssetMaintenanceController::class);
    Route::apiResource('asset-movements', AssetMovementController::class);
    Route::apiResource('asset-disposals', AssetDisposalController::class);
    Route::apiResource('asset-documents', AssetDocumentController::class);

    // Announcements
    Route::get('announcements/latest', [AnnouncementController::class, 'latest']);
    Route::apiResource('announcements', AnnouncementController::class);
    
    // Reflections
    Route::get('reflections/latest', [ReflectionController::class, 'latest']);
    Route::apiResource('reflections', ReflectionController::class);

    Route::middleware('check.acl')->group(function () {
        Route::resource('members', MemberController::class);
        Route::resource('events', EventController::class);  
    });

    // Route Member by User (Mobile)
    Route::post('/mobile/members', [MemberController::class, 'storeMemberMe']);
    Route::get('/mobile/members', [MemberController::class, 'getMemberMe']);
    Route::put('/mobile/members/update', [MemberController::class, 'updateMemberMe']);
});
