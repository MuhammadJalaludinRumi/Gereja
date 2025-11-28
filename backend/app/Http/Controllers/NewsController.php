<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return response()->json(News::orderBy('date_post', 'desc')->get());
    }

    public function show($id)
    {
        return response()->json(News::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_post' => 'required|date',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'status' => 'required|boolean'
        ]);

        $news = News::create($data);
        return response()->json($news, 201);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $data = $request->validate([
            'date_post' => 'sometimes|date',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'thumbnail' => 'sometimes|string|max:255',
            'image' => 'sometimes|string|max:255',
            'status' => 'sometimes|boolean',
            'show_on_login' => 'sometimes|boolean'
        ]);

        $news->update($data);
        return response()->json($news);
    }

    public function newsForLogin()
    {
        return News::where('status', 1)
            ->where('show_on_login', 1)
            ->orderBy('date_post', 'desc')
            ->first();
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['message' => 'deleted']);
    }
}
