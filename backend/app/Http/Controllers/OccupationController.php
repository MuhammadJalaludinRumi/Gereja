<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function index(Request $request)
    {
        $query = Occupation::with('memberData');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('memberData', function ($mq) use ($search) {
                    $mq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('company', 'like', "%$search%")
                ->orWhere('position', 'like', "%$search%");
                });
        }            

        $occupations = $query->paginate(10);
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
