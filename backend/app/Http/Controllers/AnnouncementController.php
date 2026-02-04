<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        return response()->json(Announcement::where('organization_id', Auth::user()->role->organization_id)->orderBy('date_post', 'desc')->get());
    }

    public function latest()
    {
        return response()->json(Announcement::where('organization_id', Auth::user()->role->organization_id)->latest('date_post')->first());
    }

    public function show($id)
    {
        return response()->json(Announcement::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_post' => 'required|date',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|boolean'
        ]);

        $data['organization_id'] = Auth::user()->role->organization_id; 

        $announcement = Announcement::create($data);
        return response()->json($announcement, 201);
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $data = $request->validate([
            'date_post' => 'sometimes|date',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'status' => 'sometimes|boolean',
        ]);

        $announcement->update($data);
        return response()->json($announcement);
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return response()->json(['message' => 'deleted']);
    }
}
