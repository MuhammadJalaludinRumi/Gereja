<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        return response()->json(Education::with('memberData')->get());
    }

    public function show($id)
    {
        return Education::with('memberData')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member' => 'required|integer',
            'level' => 'required|string',
            'institution' => 'required|string',
            'major' => 'nullable|string',
            'year_graduate' => 'nullable|integer'
        ]);

        return Education::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $education = Education::findOrFail($id);

        $request->validate([
            'member' => 'required|integer',
            'level' => 'required|string',
            'institution' => 'required|string',
            'major' => 'nullable|string',
            'year_graduate' => 'nullable|integer'
        ]);

        $education->update($request->all());

        return $education;
    }

    public function destroy($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return response()->json(['message' => 'Berhasil dihapus cuy']);
    }
}
