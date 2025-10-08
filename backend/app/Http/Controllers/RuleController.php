<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    // 🔹 GET /api/rules
    public function index()
    {
        return response()->json(Rule::all());
    }

    // 🔹 POST /api/rules
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer',
            'acl_id' => 'required|integer',
            'permission' => 'required|boolean',
        ]);

        $rule = Rule::create($validated);
        return response()->json($rule, 201);
    }

    // 🔹 GET /api/rules/{id}
    public function show($id)
    {
        $rule = Rule::find($id);
        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }
        return response()->json($rule);
    }

    // 🔹 PUT /api/rules/{id}
    public function update(Request $request, $id)
    {
        $rule = Rule::find($id);
        if (!$rule) {
            return response()->json(['message' => 'Rule not found'], 404);
        }

        $validated = $request->validate([
            'role_id' => 'required|integer',
            'acl_id' => 'required|integer',
            'permission' => 'required|boolean',
        ]);

        $rule->update($validated);
        return response()->json($rule);
    }

    // 🔹 DELETE /api/rules/{id}
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
