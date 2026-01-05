<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserAuthority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua user beserta relasi role-nya
        $users = User::with('role')->get();
        return response()->json($users);
    }


    public function userRegister(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'email' => 'nullable|required_without:phone|email|unique:users,email',
            'phone' => 'nullable|required_without:email|regex:/^[0-9]{10,15}$/|unique:users,phone',
            'is_active' => 'required|boolean'
        ]);

        $role = Role::firstOrCreate(
            ['name' => 'user'],
            ['organization_id' => 4]
        );

        $validated['role_id'] = $role->id;
        $validated['password'] = Hash::make($validated['password']);
        
        $firstName = Str::lower(
            preg_replace(
                '/[^a-zA-Z0-9]/',
                '',
                explode(' ', trim($validated['name']))[0]
            )
        );

        do {
            $validated['username'] = $firstName . '_' . now()->format('YmdHis') . rand(100, 999);
        } while (User::where('username', $validated['username'])->exists());

        $user = User::create($validated)->load('role');

        UserAuthority::create([
            'user_id' => $user->id,
            'role_id' => $validated['role_id'],
        ]);

        return response()->json($user, 201);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'is_active' => 'required|boolean',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated)->load('role');

        UserAuthority::create([
            'user_id' => $user->id,
            'role_id' => $validated['role_id'],
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'name' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'role_id' => 'sometimes|integer|exists:roles,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['role_id'])) {
            UserAuthority::updateOrCreate(
                ['user_id' => $user->id],
                ['role_id' => $validated['role_id']]
            );
        }

        return response()->json($user->load('role'));
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6'
        ]);

        $user->username = $validated['username'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('role')
        ]);
    }

    public function sendResetPasswordLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        // Buat token acak
        $token = Str::random(60);

        // Simpan token ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Kirim email (bisa ganti ke Mail::to(...) sesuai setup mailer)
        Mail::raw("Gunakan token ini untuk reset password Anda: $token", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password');
        });

        return response()->json(['message' => 'Link reset password sudah dikirim ke email']);
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa'], 400);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}
