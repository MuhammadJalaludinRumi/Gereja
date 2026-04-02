<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Rule::with([
            'role:id,name',
            'acl:id,name'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->orWhereHas('role', fn ($r) => 
                    $r->where('name', 'like', "%$search%")
                )
                ->orWhereHas('acl', fn ($a) => 
                    $a->where('name', 'like', "%$search%")
                );
            });
        }

        return response()->json($query->get());
    }

    /**
     * POST /api/rules
     * Menyimpan rule baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'acl_id' => 'required|integer|exists:acls,id',
            'permission' => 'required|boolean',
        ]);

        $rule = Rule::create($validated);
        return response()->json($rule, 201);
    }

    /**
     * GET /api/rules/{id}
     * Menampilkan satu rule berdasarkan ID
     */
    public function show($id)
    {
        $rule = Rule::with(['role', 'acl'])->find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        return response()->json($rule);
    }

    /**
     * PUT /api/rules/{id}
     * Update rule berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        $rule = Rule::find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        $validated = $request->validate([
            'role_id' => 'required|integer|exists:roles,id',
            'acl_id' => 'required|integer|exists:acls,id',
            'permission' => 'required|boolean',
        ]);

        $rule->update($validated);
        return response()->json($rule);
    }

    /**
     * DELETE /api/rules/{id}
     * Menghapus rule berdasarkan ID
     */
    public function destroy($id)
    {
        $rule = Rule::find($id);

        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        $rule->delete();
        return response()->json(['message' => 'Rule deleted successfully']);
    }
}
