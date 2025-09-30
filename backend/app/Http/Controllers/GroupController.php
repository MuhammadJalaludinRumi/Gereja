<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return response()->json(Group::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'city'    => 'nullable|string',
            'phone'   => 'nullable|string',
            'email'   => 'nullable|email',
            'website' => 'nullable|string',
            'logo'    => 'nullable|string',
            'founded' => 'nullable|date',
            'legal'   => 'nullable|string',
        ]);

        $group = Group::create($data);
        return response()->json($group, 201);
    }

    public function show(Group $group)
    {
        return response()->json($group);
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'city'    => 'nullable|integer',
            'phone'   => 'nullable|string',
            'email'   => 'nullable|email',
            'website' => 'nullable|string',
            'logo'    => 'nullable|string',
            'founded' => 'nullable|date',
            'legal'   => 'nullable|string',
        ]);

        $group->update($data);
        return response()->json($group);
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(null, 204);
    }
}
