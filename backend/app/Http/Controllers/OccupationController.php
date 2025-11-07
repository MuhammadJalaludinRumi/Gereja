<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function index()
    {
        // Fetch semua data occupation + member
        $occupations = Occupation::with('memberData')->get();
        return response()->json($occupations);
    }

    public function show($id)
    {
        $occupation = Occupation::with('memberData')->find($id);
        if (!$occupation) {
            return response()->json(['message' => 'Occupation not found'], 404);
        }
        return response()->json($occupation);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member' => 'required|exists:members,id',
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'year_start' => 'required|integer',
            'year_end' => 'nullable|integer',
        ]);

        $occupation = Occupation::create($data);
        return response()->json($occupation, 201);
    }

    public function update(Request $request, $id)
    {
        $occupation = Occupation::find($id);
        if (!$occupation) {
            return response()->json(['message' => 'Occupation not found'], 404);
        }

        $data = $request->validate([
            'member' => 'required|exists:members,id',
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'year_start' => 'required|integer',
            'year_end' => 'nullable|integer',
        ]);

        $occupation->update($data);
        return response()->json($occupation);
    }

    public function destroy($id)
    {
        $occupation = Occupation::find($id);
        if (!$occupation) {
            return response()->json(['message' => 'Occupation not found'], 404);
        }

        $occupation->delete();
        return response()->json(['message' => 'Occupation deleted']);
    }
}
