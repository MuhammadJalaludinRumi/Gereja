<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    /**
     * List semua kategori
     */
    public function index()
    {
        return AssetCategory::all();
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'nullable|string'
        ]);

        $category = AssetCategory::create($request->only('name', 'description'));

        return response()->json($category, 201);
    }

    /**
     * Menampilkan satu kategori
     */
    public function show($id)
    {
        $category = AssetCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|max:150',
            'description' => 'nullable|string'
        ]);

        $category->update($request->only('name', 'description'));

        return response()->json($category);
    }

    /**
     * Hapus kategori
     */
    public function destroy($id)
    {
        $category = AssetCategory::findOrFail($id);

        // jika ada relasi aset bisa optional ON DELETE RESTRICT dari DB
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
