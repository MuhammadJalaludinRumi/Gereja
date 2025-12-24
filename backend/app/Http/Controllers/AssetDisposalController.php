<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDisposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetDisposalController extends Controller
{
    public function index()
    {
        return AssetDisposal::with('asset')
            ->orderByDesc('disposal_date')
            ->get();
    }

    public function show($id)
    {
        return AssetDisposal::with('asset')->findOrFail($id);
    }

    // CREATE DISPOSAL (FINAL ACTION)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'       => 'required|exists:assets,id',
            'disposal_date'  => 'required|date',
            'disposal_type'  => 'required|in:dijual,rusak,hilang',
            'value'          => 'nullable|numeric',
            'notes'          => 'nullable|string',
        ]);

        $asset = Asset::with('disposal')->findOrFail($validated['asset_id']);

        // 🚫 asset sudah disposed
        if ($asset->disposal) {
            return response()->json([
                'message' => 'Asset sudah disposed sebelumnya'
            ], 422);
        }

        DB::transaction(function () use ($asset, $validated) {
            AssetDisposal::create($validated);

            // 🔒 lock asset
            $asset->update([
                'status' => 'disposed'
            ]);
        });

        return response()->json([
            'message' => 'Asset berhasil disposed'
        ], 201);
    }

    // UPDATE ❌ (DISABLED)
    public function update()
    {
        return response()->json([
            'message' => 'Asset disposal tidak bisa diubah karena bersifat final'
        ], 405);
    }

    // DELETE ❌ (DISABLED)
    public function destroy()
    {
        return response()->json([
            'message' => 'Asset disposal tidak bisa dihapus karena bersifat final'
        ], 405);
    }
}
