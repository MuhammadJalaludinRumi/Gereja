<?php

namespace App\Http\Controllers;

use App\Models\FamilyRelation;
use Illuminate\Http\Request;

class FamilyRelationController extends Controller
{
    public function save(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|integer',
            'relations' => 'required|array',
            'relations.*.relation_type' => 'required|string',
            'relations.*.related_member_id' => 'nullable|integer',
            'relations.*.related_auxiliary_id' => 'nullable|integer',
            'relations.*.nik_manual' => 'nullable|string',
            'relations.*.nama_manual' => 'nullable|string',
        ]);

        // Hapus semua relasi lama
        FamilyRelation::where('member_id', $data['member_id'])->delete();

        // Insert baru
        foreach ($data['relations'] as $rel) {
            FamilyRelation::create([
                'member_id' => $data['member_id'],

                'related_member_id' => $rel['related_member_id'] ?? null,
                'related_auxiliary_id' => $rel['related_auxiliary_id'] ?? null,

                'nik_manual' => $rel['nik_manual'] ?? null,
                'nama_manual' => $rel['nama_manual'] ?? null,

                'relation_type' => $rel['relation_type'],
            ]);
        }

        return response()->json(['message' => 'Relations saved']);
    }

    public function byMember($id)
    {
        $relations = FamilyRelation::with([
            'relatedMember',
            'relatedAux',
        ])->where('member_id', $id)->get();

        return response()->json($relations);
    }
}
