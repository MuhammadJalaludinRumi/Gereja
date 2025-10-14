<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // 1️⃣ Validasi input
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            // 2️⃣ Siapkan credentials
            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
            ];

            // 3️⃣ Attempt pakai guard web
            if (!Auth::guard('web')->attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // 4️⃣ Ambil user
            $user = Auth::guard('web')->user();

            // 5️⃣ Hapus token lama
            $user->tokens()->delete();

            // 6️⃣ Generate token baru
            $token = $user->createToken('auth_token')->plainTextToken;

            // 7️⃣ Update last_login
            $user->last_login = now();
            $user->save();

            // 8️⃣ Return response
            return response()->json([
                'message'    => 'Login successful',
                'user'       => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'username'   => $user->username,
                    'role_id'    => $user->role_id,
                    'is_active'  => $user->is_active,
                    'last_login' => $user->last_login,
                ],
                'token' => $token,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
