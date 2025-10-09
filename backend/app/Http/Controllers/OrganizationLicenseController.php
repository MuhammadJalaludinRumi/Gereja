<?php

namespace App\Http\Controllers;

use App\Models\OrganizationLicense;
use Illuminate\Http\Request;

class OrganizationLicenseController extends Controller
{
    public function index()
    {
        $data = OrganizationLicense::with(['organization', 'license'])->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $item = OrganizationLicense::find($id);

        if (!$item) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($item);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|integer',
            'license_id' => 'required|integer',
            'max_member' => 'required|integer',
            'is_active' => 'required|boolean',
            'expiry' => 'required|date',
        ]);

        $license = OrganizationLicense::create($validated);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $license
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $license = OrganizationLicense::find($id);

        if (!$license) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'organization_id' => 'required|integer',
            'license_id' => 'required|integer',
            'max_member' => 'required|integer',
            'is_active' => 'required|boolean',
            'expiry' => 'required|date',
        ]);

        $license->update($validated);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        $license = OrganizationLicense::find($id);

        if (!$license) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $license->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
