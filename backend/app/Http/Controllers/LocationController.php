<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return Location::with('children')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:locations,id',
            'name' => 'required|max:150',
            'description' => 'nullable|string',
        ]);

        $loc = Location::create($request->only('parent_id', 'name', 'description'));

        return response()->json($loc, 201);
    }

    public function show($id)
    {
        return Location::with('children')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $loc = Location::findOrFail($id);

        $request->validate([
            'parent_id' => 'nullable|exists:locations,id',
            'name' => 'required|max:150',
            'description' => 'nullable|string',
        ]);

        $loc->update($request->only('parent_id', 'name', 'description'));

        return response()->json($loc);
    }

    public function destroy($id)
    {
        $loc = Location::findOrFail($id);

        $loc->delete();

        return response()->json(['message' => 'Location deleted']);
    }
}
