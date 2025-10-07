<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        return response()->json(License::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
        ]);

        $license = License::create($validated);
        return response()->json($license, 201);
    }

    public function show($id)
    {
        $license = License::findOrFail($id);
        return response()->json($license);
    }

    public function update(Request $request, $id)
    {
        $license = License::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
        ]);

        $license->update($validated);
        return response()->json($license);
    }

    public function destroy($id)
    {
        $license = License::findOrFail($id);
        $license->delete();

        return response()->json(null, 204);
    }
}
