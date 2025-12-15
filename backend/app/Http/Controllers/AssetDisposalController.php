<?php

namespace App\Http\Controllers;

use App\Models\AssetDisposal;
use Illuminate\Http\Request;

class AssetDisposalController extends Controller
{
    public function index()
    {
        $data = AssetDisposal::with('asset')->orderBy('id', 'desc')->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $data = AssetDisposal::with('asset')->findOrFail($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'disposal_date' => 'required|date',
            'disposal_type' => 'required|in:dijual,rusak,hilang',
            'value' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $disposal = AssetDisposal::create($validated);

        return response()->json([
            'message' => 'Asset disposal berhasil dibuat',
            'data' => $disposal
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $disposal = AssetDisposal::findOrFail($id);

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'disposal_date' => 'required|date',
            'disposal_type' => 'required|in:dijual,rusak,hilang',
            'value' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $disposal->update($validated);

        return response()->json([
            'message' => 'Asset disposal berhasil diupdate',
            'data' => $disposal
        ]);
    }

    public function destroy($id)
    {
        $disposal = AssetDisposal::findOrFail($id);
        $disposal->delete();

        return response()->json([
            'message' => 'Asset disposal berhasil dihapus'
        ]);
    }
}
