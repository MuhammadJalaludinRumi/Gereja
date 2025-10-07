<?php

namespace App\Http\Controllers;

use App\Models\UserAuthority;
use Illuminate\Http\Request;

class UserAuthorityController extends Controller
{
    // GET /api/user-authorities
    public function index()
    {
        return response()->json(UserAuthority::all());
    }

    // GET /api/user-authorities/{id}
    public function show($id)
    {
        $userAuthority = UserAuthority::find($id);

        if (!$userAuthority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        return response()->json($userAuthority);
    }

    // POST /api/user-authorities
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required|integer',
            'role' => 'required|integer',
        ]);

        $userAuthority = UserAuthority::create($validated);

        return response()->json($userAuthority, 201);
    }

    // PUT /api/user-authorities/{id}
    public function update(Request $request, $id)
    {
        $userAuthority = UserAuthority::find($id);

        if (!$userAuthority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        $validated = $request->validate([
            'user' => 'required|integer',
            'role' => 'required|integer',
        ]);

        $userAuthority->update($validated);

        return response()->json($userAuthority);
    }

    // DELETE /api/user-authorities/{id}
    public function destroy($id)
    {
        $userAuthority = UserAuthority::find($id);

        if (!$userAuthority) {
            return response()->json(['message' => 'User Authority not found'], 404);
        }

        $userAuthority->delete();

        return response()->json(['message' => 'User Authority deleted successfully']);
    }
}
