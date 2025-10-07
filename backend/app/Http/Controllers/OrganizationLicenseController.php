<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationLicenseController extends Controller
{
    public function index()
    {
        $data = DB::table('organization_licenses')->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $item = DB::table('organization_licenses')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|integer',
            'license_id' => 'required|integer',
            'max_member' => 'required|integer',
            'is_active' => 'required|boolean',
            'expiry' => 'required|date',
        ]);

        $id = DB::table('organization_licenses')->insertGetId([
            'organization_id' => $request->organization_id,
            'license_id' => $request->license_id,
            'max_member' => $request->max_member,
            'is_active' => $request->is_active,
            'expiry' => $request->expiry,
            'created_at' => now(),
        ]);

        return response()->json(['id' => $id], 201);
    }

    public function update(Request $request, $id)
    {
        $item = DB::table('organization_licenses')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }

        DB::table('organization_licenses')->where('id', $id)->update([
            'organization_id' => $request->organization_id,
            'license_id' => $request->license_id,
            'max_member' => $request->max_member,
            'is_active' => $request->is_active,
            'expiry' => $request->expiry,
        ]);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        DB::table('organization_licenses')->where('id', $id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
