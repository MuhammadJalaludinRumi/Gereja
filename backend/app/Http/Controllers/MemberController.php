<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        // eager load same as OrganizationController
        $members = Member::with('city')
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json($members);
    }

    public function show($id)
    {
        return response()->json(Member::with('city')->findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_local' => 'nullable|string',
            'name' => 'required|string',
            'id_type' => 'nullable|string',
            'id_number' => 'nullable|string',
            'dob' => 'nullable|date',
            'pob' => 'nullable|string',
            'nationality' => 'nullable|string',
            'ethnic' => 'nullable|string',
            'sex' => 'nullable|string|max:1',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|integer|exists:cities,id', // tetap 'city'
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
            'marriage' => 'nullable|string',
            'is_deceased' => 'boolean',
            'is_active' => 'boolean',
            'family_id' => 'nullable|string',
            'family_relation' => 'nullable|string',
            'religion' => 'nullable|string',
            'blood' => 'nullable|string',
            'baptist_date' => 'nullable|date',
            'baptist_place' => 'nullable|string',
            'baptist_host_id' => 'nullable|string',
            'baptist_host_name' => 'nullable|string',
            'consecrate_date' => 'nullable|date',
            'consecrate_place' => 'nullable|string',
            'consecrate_host_id' => 'nullable|string',
            'consecrate_host_name' => 'nullable|string',
            'attest_date' => 'nullable|date',
            'attest_origin' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 's3');
            $data['photo'] = Storage::disk('s3')->url($path);
        }

        $member = Member::create($data);

        // load relasi untuk response
        $member->load('city');

        return response()->json($member, 201);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $data = $request->validate([
            'id_local' => 'nullable|string',
            'name' => 'nullable|string',
            'id_type' => 'nullable|string',
            'id_number' => 'nullable|string',
            'dob' => 'nullable|date',
            'pob' => 'nullable|string',
            'nationality' => 'nullable|string',
            'ethnic' => 'nullable|string',
            'sex' => 'nullable|string|max:1',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|integer|exists:cities,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:2048',
            'marriage' => 'nullable|string',
            'is_deceased' => 'boolean',
            'is_active' => 'boolean',
            'family_id' => 'nullable|string',
            'family_relation' => 'nullable|string',
            'religion' => 'nullable|string',
            'blood' => 'nullable|string',
            'baptist_date' => 'nullable|date',
            'baptist_place' => 'nullable|string',
            'baptist_host_id' => 'nullable|string',
            'baptist_host_name' => 'nullable|string',
            'consecrate_date' => 'nullable|date',
            'consecrate_place' => 'nullable|string',
            'consecrate_host_id' => 'nullable|string',
            'consecrate_host_name' => 'nullable|string',
            'attest_date' => 'nullable|date',
            'attest_origin' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 's3');
            $data['photo'] = Storage::disk('s3')->url($path);
        }

        $member->update($data);
        $member->load('city');

        return response()->json($member);
    }

    public function destroy($id)
    {
        Member::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}
