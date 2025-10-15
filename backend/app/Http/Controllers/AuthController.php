<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('username', $request->username)->first();

            if (!$user) {
                return response()->json(['message' => 'Username tidak ditemukan'], 401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Password salah'], 401);
            }

            if ($user->is_active != 1) {
                return response()->json(['message' => 'Akun diblokir atau tidak aktif'], 401);
            }

            $user->update(['last_login' => now()]);

            $token = $user->createToken('auth-token')->plainTextToken;

            $isProd = Config::get('app.env') === 'production';

            // Kalo prod, kirim token di cookie Secure & HttpOnly
            if ($isProd) {
                $cookie = cookie('token', $token, 60*24*7, '/', null, true, true); // 7 hari
                return response()->json([
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'role_id' => $user->role_id,
                        'is_active' => $user->is_active,
                        'last_login' => $user->last_login,
                    ]
                ])->cookie($cookie);
            }

            // Kalo dev, tetap pake bearer token
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role_id' => $user->role_id,
                    'is_active' => $user->is_active,
                    'last_login' => $user->last_login,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $isProd = Config::get('app.env') === 'production';
        $response = response()->json(['message' => 'Logged out successfully']);

        // Hapus cookie kalo prod
        if ($isProd) {
            $response->withCookie(Cookie::forget('token'));
        }

        return $response;
    }
}
