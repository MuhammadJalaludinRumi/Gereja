<?php

namespace App\Http\Controllers;

use App\Models\UserAuthority;
use App\Models\User;
use Illuminate\Http\Request;

class UserAuthorityController extends Controller
{
    public function index(Request $request)
    {
        $query = UserAuthority::with(['user', 'role']);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query = $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('role', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%$search%");
                });
            });
        }

        $per_page = (int) $request->input('per_page', 10);

        $authorities = $query->paginate($per_page);

        return response()->json($authorities);
    }

    public function show($id)
    {
        $authority = UserAuthority::with(['user', 'role'])->find($id);

        if (!$authority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        return response()->json($authority);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // Buat record authority
        $authority = UserAuthority::create($validated);

        // Sinkronin role_id di tabel users
        $user = User::find($validated['user_id']);
        if ($user) {
            $user->update(['role_id' => $validated['role_id']]);
        }

        return response()->json([
            'message' => 'User Authority created successfully',
            'data' => $authority->load(['user', 'role'])
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $authority = UserAuthority::find($id);

        if (!$authority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // Update authority
        $authority->update($validated);

        // Sinkronin role_id di tabel users
        $user = User::find($validated['user_id']);
        if ($user) {
            $user->update(['role_id' => $validated['role_id']]);
        }

        return response()->json([
            'message' => 'User Authority updated successfully',
            'data' => $authority->load(['user', 'role'])
        ]);
    }

    public function destroy($id)
    {
        $authority = UserAuthority::find($id);

        if (!$authority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        // Optional: reset role_id user jadi null biar konsisten
        $user = User::find($authority->user_id);
        if ($user) {
            $user->update(['role_id' => null]);
        }

        $authority->delete();

        return response()->json(['message' => 'User Authority deleted successfully']);
    }
}
