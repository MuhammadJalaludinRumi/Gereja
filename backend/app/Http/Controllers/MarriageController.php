<?php

namespace App\Http\Controllers;

use App\Models\Marriage;
use Illuminate\Http\Request;

class MarriageController extends Controller
{
    public function index()
    {
        return Marriage::with([
            'brideMember',
            'groomMember',
            'priestMember'
        ])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bride' => 'nullable|exists:members,id',
            'bride_name' => 'required|string',
            'groom' => 'nullable|exists:members,id',
            'groom_name' => 'required|string',
            'date' => 'required|date',
            'venue' => 'nullable|string',
            'priest' => 'nullable|exists:members,id',
            'priest_name' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);

        $marriage = Marriage::create($data);

        // **INI FIX-nya**
        $marriage->load(['brideMember', 'groomMember', 'priestMember']);

        return response()->json([
            'message' => 'Data marriage berhasil dibuat.',
            'data' => $marriage
        ], 201);
    }

    public function show($id)
    {
        $marriage = Marriage::with([
            'brideMember',
            'groomMember',
            'priestMember'
        ])->findOrFail($id);

        return response()->json($marriage);
    }

    public function update(Request $request, $id)
    {
        $marriage = Marriage::findOrFail($id);

        $data = $request->validate([
            'bride' => 'nullable|exists:members,id',
            'bride_name' => 'required|string',
            'groom' => 'nullable|exists:members,id',
            'groom_name' => 'required|string',
            'date' => 'required|date',
            'venue' => 'nullable|string',
            'priest' => 'nullable|exists:members,id',
            'priest_name' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);

        $marriage->update($data);

        // **INI FIX-nya**
        $marriage->load(['brideMember', 'groomMember', 'priestMember']);

        return response()->json([
            'message' => 'Data marriage berhasil diupdate.',
            'data' => $marriage
        ]);
    }

    public function destroy($id)
    {
        $marriage = Marriage::findOrFail($id);
        $marriage->delete();

        return response()->json([
            'message' => 'Marriage berhasil dihapus.'
        ]);
    }
}
