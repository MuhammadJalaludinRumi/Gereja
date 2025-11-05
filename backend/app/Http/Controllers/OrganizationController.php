<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('city')->get();
        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|integer|exists:cities,id', // ✅ FIX: integer, bukan string
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'group_id'     => 'nullable|integer|exists:groups,id', // ✅ tambah exists validation
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|file|image|max:2048',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        // Upload ke S3 kalau ada file logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $org = Organization::create($data);

        // Load relationship untuk response
        $org->load('city');

        return response()->json(['data' => $org], 201);
    }

    public function show($id)
    {
        $org = Organization::with('city')->findOrFail($id);
        return response()->json(['data' => $org]);
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|integer|exists:cities,id',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'group_id'     => 'nullable|integer|exists:groups,id',
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|file|image|max:2048',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        // Upload baru kalau ada logo baru
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $org->update($data);
        $org->load('city');

        return response()->json(['data' => $org]);
    }

    public function destroy($id)
    {
        $org = Organization::findOrFail($id);
        $org->delete();

        return response()->json(null, 204);
    }
}
