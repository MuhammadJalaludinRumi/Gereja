<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::with('organization');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")

                ->orWhereHas('organization', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        $roles = $query->get();

        return response()->json($roles);
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
