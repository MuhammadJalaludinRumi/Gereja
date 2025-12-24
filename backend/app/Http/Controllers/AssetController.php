<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        return Asset::with([
            'category',
            'location',
            'disposal'
        ])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => 'required|max:100|unique:assets,asset_code',
            'name' => 'required|max:150',
            'category_id' => 'required|exists:asset_categories,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'condition' => 'required|in:baik,rusak ringan,rusak berat,hilang',
            'vendor' => 'nullable|string|max:150',
            'notes' => 'nullable|string',

            // 🔥 image validation
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /* =========================
         * UPLOAD IMAGE TO AWS S3
         * ========================= */
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store(
                'assets',
                's3'
            );

            // optional: public access
            Storage::disk('s3')->setVisibility($path, 'public');

            $validated['image'] = $path;
        }

        $asset = Asset::create([
            ...$validated,
            'status' => 'available',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json(
            $asset->load(['category', 'location']),
            201
        );
    }

    public function show($id)
    {
        return Asset::with([
            'category',
            'location',
            'movements.fromLocation',
            'movements.toLocation',
            'disposal'
        ])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        // 🚫 kalau sudah disposed → freeze
        if ($asset->disposal) {
            return response()->json([
                'message' => 'Asset sudah disposed dan tidak bisa diubah'
            ], 422);
        }

        $validated = $request->validate([
            'asset_code' => 'sometimes|required|max:100|unique:assets,asset_code,' . $asset->id,
            'name' => 'sometimes|required|max:150',
            'category_id' => 'sometimes|required|exists:asset_categories,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'condition' => 'nullable|in:baik,rusak ringan,rusak berat,hilang',
            'vendor' => 'nullable|string|max:150',
            'notes' => 'nullable|string',

            // 🔥 image optional saat update
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /* =========================
         * REPLACE IMAGE (S3)
         * ========================= */
        if ($request->hasFile('image')) {

            // hapus image lama kalau ada
            if ($asset->image) {
                Storage::disk('s3')->delete($asset->image);
            }

            $path = $request->file('image')->store(
                'assets',
                's3'
            );

            Storage::disk('s3')->setVisibility($path, 'public');

            $validated['image'] = $path;
        }

        $asset->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return response()->json(
            $asset->load(['category', 'location'])
        );
    }

    public function destroy($id)
    {
        $asset = Asset::with(['movements', 'disposal'])->findOrFail($id);

        if ($asset->movements->count() > 0 || $asset->disposal) {
            return response()->json([
                'message' => 'Asset memiliki riwayat movement atau disposal dan tidak bisa dihapus'
            ], 422);
        }

        // 🧹 hapus image di S3
        if ($asset->image) {
            Storage::disk('s3')->delete($asset->image);
        }

        $asset->delete();

        return response()->json([
            'message' => 'Asset berhasil dihapus'
        ]);
    }
}
