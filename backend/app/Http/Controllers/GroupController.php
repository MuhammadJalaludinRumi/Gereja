<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index()
    {
        $group = Group::with('city')->get();

        return response()->json($group);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string',
            'city'    => 'required|integer|exists:cities,id',
            'phone'   => 'required|string',
            'email'   => 'required|email',
            'website' => 'required|string',
            'logo'    => 'required|file|image|max:2048',
            'founded' => 'required|date',
            'legal'   => 'required|string',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('groups', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $group = Group::create($data);
        return response()->json($group, 201);
    }

    public function show(Group $group)
    {
        $group->load('city');
        return response()->json($group);
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'city'    => 'sometimes|integer|exists:cities,id',
            'phone'   => 'sometimes|string',
            'email'   => 'sometimes|email',
            'website' => 'sometimes|string',
            'logo'    => 'sometimes|file|image|max:2048',
            'founded' => 'sometimes|date',
            'legal'   => 'sometimes|string',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('groups', 's3');
            $url = Storage::disk('s3')->url($path);
            $data['logo'] = $url;
        }

        $group->update($data);
        return response()->json($group);
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json(null, 204);
    }
}
