<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        // Username tidak ditemukan
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Username tidak ditemukan.'
            ], 401);
        }

        $user->load(['role', 'role.organization']);

        // Password salah
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah.'
            ], 401);
        }

        // Akun nonaktif
        if ($user->is_active != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Akun diblokir atau tidak aktif.'
            ], 403);
        }

        // Update last login
        $user->update(['last_login' => now()]);

        // Production: session-based
        if (app()->environment('production')) {
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil.',
                'user' => $user
            ], 200);
        }

        // Dev/local: token-based
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function mobileLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        // Username tidak ditemukan
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Username tidak ditemukan.'
            ], 401);
        }

        $user->load(['role', 'role.organization']);

        // Password salah
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah.'
            ], 401);
        }

        // Akun nonaktif
        if ($user->is_active != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Akun diblokir atau tidak aktif.'
            ], 403);
        }

        // Update last login
        $user->update(['last_login' => now()]);

        $token = $user->createToken(
            'mobile',
            ['*'],
            now()->addHours(2)
        )->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        if (app()->environment('production')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['status' => true, 'message' => 'Logout berhasil.']);
    }

    public function mobileLogout(Request $request)
    {

        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil.'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak terautentikasi.'
            ], 200);
        }

        return response()->json([
            'status' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role_id' => $user->role_id,
                'is_active' => $user->is_active,
                'last_login' => $user->last_login,
            ]
        ], 200);
    }
}
