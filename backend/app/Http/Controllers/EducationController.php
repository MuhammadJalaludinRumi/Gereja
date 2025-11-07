<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        return Education::all();
    }

    public function show($id)
    {
        return Education::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member' => 'required|integer',
            'level' => 'required|string',
            'institution' => 'required|string',
            'major' => 'required|string',
            'year_graduate' => 'required|integer'
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
            'major' => 'required|string',
            'year_graduate' => 'required|integer'
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
