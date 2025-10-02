<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        return Province::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $province = Province::create($request->only('name'));

        return response()->json($province, 201);
    }

    public function show($id)
    {
        return Province::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $province = Province::findOrFail($id);
        $province->update($request->only('name'));

        return response()->json($province);
    }

    public function destroy($id)
    {
        $province = Province::findOrFail($id);
        $province->delete();

        return response()->json(null, 204);
    }
}
