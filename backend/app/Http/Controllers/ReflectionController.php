<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function index()
    {
        return response()->json(Reflection::orderBy('date_post', 'desc')->get());
    }

    public function latest()
    {
        return response()->json(Reflection::latest('date_post')->first());
    }

    public function show($id)
    {
        return response()->json(Reflection::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_post' => 'required|date',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $reflection = Reflection::create($data);
        return response()->json($reflection, 201);
    }

    public function update(Request $request, $id)
    {
        $reflection = Reflection::findOrFail($id);
        $data = $request->validate([
            'date_post' => 'sometimes|date',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
        ]);

        $reflection->update($data);
        return response()->json($reflection);
    }

    public function destroy($id)
    {
        $reflection = Reflection::findOrFail($id);
        $reflection->delete();
        return response()->json(['message' => 'deleted']);
    }
}
