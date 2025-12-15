<?php

namespace App\Http\Controllers;

use App\Models\AssetMovement;
use Illuminate\Http\Request;

class AssetMovementController extends Controller
{
    // GET ALL
    public function index()
    {
        $data = AssetMovement::with(['asset', 'fromLocation', 'toLocation', 'user'])->get();
        return response()->json($data);
    }

    // CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'          => 'required|exists:assets,id',
            'from_location_id'  => 'required|exists:locations,id',
            'to_location_id'    => 'required|exists:locations,id',
            'moved_by'          => 'required|exists:users,id',
            'moved_at'          => 'required|date',
            'notes'             => 'nullable|string',
        ]);

        $movement = AssetMovement::create($validated);

        return response()->json([
            'message' => 'movement sukses kebuat bro',
            'data' => $movement
        ]);
    }

    // GET DETAIL
    public function show($id)
    {
        $data = AssetMovement::with(['asset', 'fromLocation', 'toLocation', 'user'])->findOrFail($id);
        return response()->json($data);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $movement = AssetMovement::findOrFail($id);

        $validated = $request->validate([
            'asset_id'          => 'sometimes|exists:assets,id',
            'from_location_id'  => 'sometimes|exists:locations,id',
            'to_location_id'    => 'sometimes|exists:locations,id',
            'moved_by'          => 'sometimes|exists:users,id',
            'moved_at'          => 'sometimes|date',
            'notes'             => 'nullable|string',
        ]);

        $movement->update($validated);

        return response()->json([
            'message' => 'movement udah diupdate cuy',
            'data' => $movement
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $movement = AssetMovement::findOrFail($id);
        $movement->delete();

        return response()->json(['message' => 'movement kehapus bro']);
    }
}
