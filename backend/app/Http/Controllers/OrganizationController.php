<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::with('city');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('website', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orWhere('website', 'like', "%$search%")
                ->orWhereHas('city', function($cq) use ($search) {
                    $cq->where('name', 'like', "%$search%");
                });
            });
        }

        $organizations = $query->get();

        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
            'address'      => 'required|string|max:255',
            'city'         => 'required|integer|exists:cities,id', // ✅ FIX: integer, bukan string
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'phone'        => 'required|string|max:50',
            'email'        => 'required|email|max:255',
            'group_id'     => 'required|integer|exists:groups,id', // ✅ tambah exists validation
            'website'      => 'required|string|max:255',
            'logo'         => 'required|file|image|max:2048',
            'founded'      => 'required|date',
            'legal'        => 'required|string|max:100',
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
        return response()->json($org);
    }

    public function update(Request $request, $id)
    {
        $org = Organization::findOrFail($id);

        $data = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'abbreviation' => 'sometimes|string|max:50',
            'address'      => 'sometimes|string|max:255',
            'city'         => 'sometimes|integer|exists:cities,id',
            'latitude'     => 'sometimes|numeric',
            'longitude'    => 'sometimes|numeric',
            'phone'        => 'sometimes|string|max:50',
            'email'        => 'sometimes|email|max:255',
            'group_id'     => 'sometimes|integer|exists:groups,id',
            'website'      => 'sometimes|string|max:255',
            'logo'         => 'sometimes|file|image|max:2048',
            'founded'      => 'sometimes|date',
            'legal'        => 'sometimes|string|max:100',
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
