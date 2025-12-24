<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetMovementController extends Controller
{
    // GET ALL
    public function index()
    {
        return AssetMovement::with([
            'asset',
            'fromLocation',
            'toLocation',
            'user'
        ])->orderByDesc('moved_at')->get();
    }

    // CREATE (PINDAH ASSET)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id'         => 'required|exists:assets,id',
            'to_location_id'   => 'required|exists:locations,id',
            'moved_at'         => 'required|date',
            'notes'            => 'nullable|string',
        ]);

        $asset = Asset::with('disposal')->findOrFail($validated['asset_id']);

        // 🚫 asset disposed gak boleh dipindahin
        if ($asset->disposal) {
            return response()->json([
                'message' => 'Asset sudah disposed dan tidak bisa dipindahkan'
            ], 422);
        }

        // 🚫 lokasi sama
        if ($asset->location_id == $validated['to_location_id']) {
            return response()->json([
                'message' => 'Lokasi tujuan sama dengan lokasi sekarang'
            ], 422);
        }

        DB::transaction(function () use ($asset, $validated) {
            AssetMovement::create([
                'asset_id'         => $asset->id,
                'from_location_id' => $asset->location_id,
                'to_location_id'   => $validated['to_location_id'],
                'moved_by'         => Auth::id(),
                'moved_at'         => $validated['moved_at'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            // 🔥 update lokasi asset
            $asset->update([
                'location_id' => $validated['to_location_id']
            ]);
        });

        return response()->json([
            'message' => 'Asset berhasil dipindahkan'
        ], 201);
    }

    // GET DETAIL
    public function show($id)
    {
        return AssetMovement::with([
            'asset',
            'fromLocation',
            'toLocation',
            'user'
        ])->findOrFail($id);
    }

    // UPDATE ❌ (movement = history)
    public function update()
    {
        return response()->json([
            'message' => 'Movement tidak bisa diubah karena merupakan data history'
        ], 405);
    }

    // DELETE ❌ (movement = history)
    public function destroy()
    {
        return response()->json([
            'message' => 'Movement tidak bisa dihapus karena merupakan data history'
        ], 405);
    }
}
