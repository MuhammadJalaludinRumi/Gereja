<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('city');

        // FILTER BY FAMILY ID (KK)
        if ($request->has('family_id') && $request->family_id !== '') {
            $query->where('family_id', $request->family_id);

            // kalau filter KK, return semua tanpa paginate
            return response()->json([
                'success' => true,
                'data' => $query->orderBy('name', 'asc')->get()
            ]);
        }

        // default paginate
        $members = $query
            ->orderBy('name', 'asc')
            ->paginate(50);

        return response()->json($members);
    }

    // opsional, boleh dipake atau dihapus karena sudah includ di index()
    public function byKK($kk)
    {
        return Member::where("family_id", $kk)->get();
    }

    public function show($id)
    {
        return response()->json(
            Member::with('city')->findOrFail($id)
        );
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
            'family_group_int' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 's3');
            $data['photo'] = Storage::disk('s3')->url($path);
        }

        $member = Member::create($data);

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

    public function search(Request $request)
    {
        $nik = $request->nik;

        $member = Member::where('id_number', $nik)
                        ->orWhere('id_local', $nik)
                        ->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }

    public function destroy($id)
    {
        Member::findOrFail($id)->delete();

        return response()->json(['message' => 'deleted']);
    }

    // Mobile Member CRU
    public function getMemberMe() {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->with('city')->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found for this user'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }

    public function storeMemberMe(Request $request) {
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
            'family_group_int' => 'nullable|string',
        ]);

        $user = Auth::user();

        $data['user_id'] = $user->id;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 's3');
            $data['photo'] = Storage::disk('s3')->url($path);
        }

        $member = Member::create($data);

        return response()->json([
            'success' => true,
            'data' => $member
        ], 201);
    }

    public function updateMemberMe(Request $request) {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();
        
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found for this user'
                ], 404);
        }

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
            'family_group_int' => 'nullable|string',
        ]);
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('members', 's3');
            $data['photo'] = Storage::disk('s3')->url($path);
        }

        $member->update($data);

        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }
}
