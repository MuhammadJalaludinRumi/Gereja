<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return Asset::with(['category', 'location'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_code' => 'required|max:100|unique:assets,asset_code',
            'name' => 'required|max:150',
            'category_id' => 'required|exists:asset_categories,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'condition' => 'required|in:baik,rusak ringan,rusak berat,hilang',
            'status' => 'required|in:available,borrowed,under_maintenance,disposed',
            'vendor' => 'nullable|string|max:150',
            'notes' => 'nullable|string'
        ]);

        $asset = Asset::create([
            ...$request->all(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json($asset, 201);
    }

    public function show($id)
    {
        return Asset::with(['category', 'location'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'asset_code' => 'sometimes|required|max:100|unique:assets,asset_code,' . $asset->id,
            'name' => 'sometimes|required|max:150',
            'category_id' => 'sometimes|required|exists:asset_categories,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'condition' => 'nullable|in:baik,rusak ringan,rusak berat,hilang',
            'status' => 'nullable|in:available,borrowed,under_maintenance,disposed',
            'vendor' => 'nullable|string|max:150',
            'notes' => 'nullable|string'
        ]);

        $asset->update([
            ...$request->all(),
            'updated_by' => auth()->id(),
        ]);

        return response()->json($asset);
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        $asset->delete();

        return response()->json(['message' => 'Asset deleted']);
    }
}
