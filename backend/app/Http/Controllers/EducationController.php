<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $query = Education::with('memberData');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('memberData', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('level', 'like', "%$search%")
                ->orWhere('institution', 'like', "%$search%")
                ->orWhere('major', 'like', "%$search%")
                ->orWhere('year_graduate', 'like', "%$search%");
                });
        } 

        $per_page = (int) $request->input('per_page', 10);

        $educations = $query
            ->orderBy('id', 'asc')
            ->paginate($per_page);
        return response()->json($educations);
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
