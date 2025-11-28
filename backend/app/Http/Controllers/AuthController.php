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

        if (!$user) {
            return response()->json(['message' => 'Username tidak ditemukan'], 401);
        }

        $user->load([
            'role',
            'role.organization'
        ]);


        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        if ($user->is_active != 1) {
            return response()->json(['message' => 'Akun diblokir atau tidak aktif'], 401);
        }

        $user->update(['last_login' => now()]);

        // Multi-env: prod pake session auth, local/dev pake token
        if (app()->environment('production')) {
            // Prod: login pake session + cookie
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            return response()->json([
                'user' => $user
            ]);
        } else {
            // Dev/local: pake token supaya frontend gampang
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }
    }

    public function logout(Request $request)
    {
        if (app()->environment('production')) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } else {
            $request->user()?->currentAccessToken()?->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Opsional: endpoint /me
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role_id' => $user->role_id,
                'is_active' => $user->is_active,
                'last_login' => $user->last_login,
            ]
        ]);
    }
}
