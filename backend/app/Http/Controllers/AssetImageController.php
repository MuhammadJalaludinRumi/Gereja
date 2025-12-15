<?php

namespace App\Http\Controllers;

use App\Models\AssetImage;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetImageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('asset_id')) {
            return response()->json(
                AssetImage::where('asset_id', $request->asset_id)->get()
            );
        }

        return response()->json(
            AssetImage::all()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id'   => 'required|integer|exists:assets,id',
            'image'      => 'required|file|image|max:4096', // max 4MB
        ]);

        // Upload ke AWS S3
        $path = $request->file('image')->store('asset-images', 's3');
        $url = Storage::disk('s3')->url($path);

        $image = AssetImage::create([
            'asset_id'   => $request->asset_id,
            'image_path' => $url,
            'created_at' => now(),
        ]);

        return response()->json(['data' => $image], 201);
    }

    public function destroy($id)
    {
        $img = AssetImage::findOrFail($id);

        // Optional: hapus file di S3
        $parsedUrl = parse_url($img->image_path, PHP_URL_PATH);
        if ($parsedUrl) {
            Storage::disk('s3')->delete(ltrim($parsedUrl, '/'));
        }

        $img->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
