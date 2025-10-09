<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::with('organization')->get();
        return response()->json($role);
    }

    public function show($id)
    {
        return response()->json(Role::findOrFail($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create($request->all());
        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'organization_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $role->update($request->all());
        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(null, 204);
    }
}
