<?php

namespace App\Http\Controllers;

use App\Models\AuxiliaryPerson;
use Illuminate\Http\Request;

class AuxiliaryPersonController extends Controller
{
    // GET /auxiliary-persons
    public function index()
    {
        return response()->json(AuxiliaryPerson::all());
    }

    // POST /auxiliary-persons
    public function store(Request $request)
    {
        // VALIDASI YANG BENAR
        $request->validate([
            'member_id'       => 'required|exists:members,id',
            'id_local'        => 'required|string|max:50',
            'name'            => 'required|string|max:150',
            'family_relation' => 'nullable|string|max:50'
        ]);

        $person = AuxiliaryPerson::create($request->all());

        return response()->json([
            'message' => 'Data created successfully',
            'data'    => $person
        ], 201);
    }

    // SEARCH BY NIK / ID LOCAL
    public function search(Request $request)
    {
        $nik = $request->nik;

        $person = AuxiliaryPerson::where('id_local', $nik)->first();

        if (!$person) {
            return response()->json([
                'success' => false,
                'message' => 'Auxiliary person not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $person
        ]);
    }

    // GET /auxiliary-persons/{id}
    public function show($id)
    {
        return response()->json(
            AuxiliaryPerson::findOrFail($id)
        );
    }

    // PUT /auxiliary-persons/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_local'        => 'string|max:50',
            'name'            => 'string|max:150',
            'family_relation' => 'string|max:50',
        ]);

        $person = AuxiliaryPerson::findOrFail($id);
        $person->update($request->all());

        return response()->json([
            'message' => 'Data updated successfully',
            'data'    => $person
        ]);
    }

    // GET berdasarkan member
    public function getByMember($memberId)
    {
        return response()->json(
            AuxiliaryPerson::where('member_id', $memberId)->get()
        );
    }

    // DELETE
    public function destroy($id)
    {
        AuxiliaryPerson::findOrFail($id)->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
