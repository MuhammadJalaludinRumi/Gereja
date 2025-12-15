<?php

namespace App\Http\Controllers;

use App\Models\AssetDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetDocumentController extends Controller
{
    public function index()
    {
        return AssetDocument::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id'    => 'required|integer|exists:assets,id',
            'type'        => 'required|in:invoice,warranty,photo,other',
            'file'        => 'required|file',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('asset-documents', 's3');
            $data['file_path'] = Storage::disk('s3')->url($path);
        }

        $doc = AssetDocument::create($data);

        return response()->json($doc, 201);
    }

    public function show($id)
    {
        return AssetDocument::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $doc = AssetDocument::findOrFail($id);

        $data = $request->validate([
            'asset_id'    => 'required|integer|exists:assets,id',
            'type'        => 'required|in:invoice,warranty,photo,other',
            'file'        => 'nullable|file',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('asset-documents', 's3');
            $data['file_path'] = Storage::disk('s3')->url($path);
        }

        $doc->update($data);

        return response()->json($doc);
    }

    public function destroy($id)
    {
        AssetDocument::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
