<?php

namespace App\Http\Controllers;

use App\Models\Acl;
use Illuminate\Http\Request;

class AclController extends Controller
{
    // GET /api/acls
    public function index(Request $request)
    {
        $query = Acl::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $acls = $query->get();

        return response()->json($acls);
    }

    // GET /api/acls/{id}
    public function show($id)
    {
        $acl = Acl::find($id);
        if (!$acl) {
            return response()->json(['message' => 'ACL not found'], 404);
        }
        return response()->json($acl);
    }

    // POST /api/acls
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $acl = Acl::create($request->only('name'));

        return response()->json([
            'message' => 'ACL created successfully',
            'data' => $acl
        ], 201);
    }

    // PUT /api/acls/{id}
    public function update(Request $request, $id)
    {
        $acl = Acl::find($id);
        if (!$acl) {
            return response()->json(['message' => 'ACL not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $acl->update($request->only('name'));

        return response()->json([
            'message' => 'ACL updated successfully',
            'data' => $acl
        ]);
    }

    // DELETE /api/acls/{id}
    public function destroy($id)
    {
        $acl = Acl::find($id);
        if (!$acl) {
            return response()->json(['message' => 'ACL not found'], 404);
        }

        $acl->delete();

        return response()->json(['message' => 'ACL deleted successfully']);
    }
}
