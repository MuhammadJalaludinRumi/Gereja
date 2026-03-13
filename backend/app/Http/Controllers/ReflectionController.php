<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReflectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reflection::where('organization_id', Auth::user()->role->organization_id);

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%");
                });
        }

        $per_page = (int) $request->input('per_page', 10);

        $reflection = $query
            ->orderBy('date_post', 'desc')
            ->paginate($per_page);

        return response()->json($reflection);
    }

    public function latest()
    {
        return response()->json(Reflection::where('organization_id', Auth::user()->role->organization_id)->latest('date_post')->first());
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

        $data['organization_id'] = Auth::user()->role->organization_id; 

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
