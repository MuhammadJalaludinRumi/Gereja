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

        // buat debug
        return response()->json($group);
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
            'logo'    => 'nullable|file|image|max:2048',
            'founded' => 'nullable|date',
            'legal'   => 'nullable|string',
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
        return response()->json($group);
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'city'    => 'nullable|string',
            'phone'   => 'nullable|string',
            'email'   => 'nullable|email',
            'website' => 'nullable|string',
            'logo'    => 'nullable|file|image|max:2048',
            'founded' => 'nullable|date',
            'legal'   => 'nullable|string',
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
