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
            'city'         => 'nullable|string|max:100',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'group_id'     => 'nullable|integer',
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|file|image|max:2048',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        // upload ke s3 kalo ada file logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $org = Organization::create($data);
        return response()->json($org, 201);
    }

    public function show($id)
    {
        $org = Organization::findOrFail($id);
        return response()->json($org);
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|integer',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'group_id'     => 'nullable|integer',
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|file|image|max:2048',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        // upload baru kalau ada logo baru
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $org->update($data);
        return response()->json($org);
    }

    public function destroy($id)
    {
        $org = Organization::findOrFail($id);
        $org->delete();

        return response()->json(null, 204);
    }
}
