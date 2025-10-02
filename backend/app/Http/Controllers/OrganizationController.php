<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        return response()->json(Organization::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'group_id'     => 'nullable|int',
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|string|max:255',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        $org = Organization::create($validated);
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

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'address'      => 'nullable|string|max:255',
            'city'         => 'nullable|string|max:100',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'website'      => 'nullable|string|max:255',
            'logo'         => 'nullable|string|max:255',
            'founded'      => 'nullable|date',
            'legal'        => 'nullable|string|max:100',
        ]);

        $org->update($validated);
        return response()->json($org);
    }

    public function destroy($id)
    {
        $org = Organization::findOrFail($id);
        $org->delete();

        return response()->json(null, 204);
    }
}
