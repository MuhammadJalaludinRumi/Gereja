<?php

namespace App\Http\Controllers;

use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    // READ (ALL)
    public function index()
    {
        return AssetMaintenance::with(['asset', 'creator'])
            ->orderBy('id', 'desc')
            ->get();
    }

    // CREATE
    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|integer',
            'maintenance_date' => 'required|date',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'performed_by' => 'required|string|max:150',
            'next_maintenance_date' => 'nullable|date',
            'created_by' => 'required|integer',
        ]);

        $create = AssetMaintenance::create($data);

        return response()->json($create, 201);
    }

    // READ (ONE)
    public function show($id)
    {
        $data = AssetMaintenance::with(['asset', 'creator'])->findOrFail($id);
        return response()->json($data);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'asset_id' => 'sometimes|integer',
            'maintenance_date' => 'sometimes|date',
            'description' => 'nullable|string',
            'cost' => 'sometimes|numeric',
            'performed_by' => 'sometimes|string|max:150',
            'next_maintenance_date' => 'nullable|date',
            'created_by' => 'sometimes|integer',
        ]);

        $maintenance = AssetMaintenance::findOrFail($id);
        $maintenance->update($data);

        return response()->json($maintenance);
    }

    // DELETE
    public function destroy($id)
    {
        $maintenance = AssetMaintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json(['message' => 'Mantap, data udah kehapus.']);
    }
}
