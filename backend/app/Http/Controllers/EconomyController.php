<?php

namespace App\Http\Controllers;

use App\Models\Economy;
use Illuminate\Http\Request;

class EconomyController extends Controller
{
    public function index()
    {
        $economies = Economy::with('member')->get();
        return response()->json($economies);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member' => 'required|integer|exists:members,id',
            'update' => 'required|date',
            'class' => 'required|string|max:255'
        ]);

        $economy = Economy::create($data);
        return response()->json($economy);
    }

    public function show($id)
    {
        $economy = Economy::with('member')->findOrFail($id);
        return response()->json($economy);
    }

    public function update(Request $request, $id)
    {
        $economy = Economy::findOrFail($id);

        $data = $request->validate([
            'member' => 'required|integer|exists:members,id',
            'update' => 'required|date',
            'class' => 'required|string|max:255'
        ]);

        $economy->update($data);
        return response()->json($economy);
    }

    public function destroy($id)
    {
        $economy = Economy::findOrFail($id);
        $economy->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
