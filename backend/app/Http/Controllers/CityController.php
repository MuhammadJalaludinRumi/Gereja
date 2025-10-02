<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    public function index()
    {
        $cities = DB::table('cities')
            ->join('province', 'cities.province', '=', 'province.id') // pakai 'province', bukan 'province_id'
            ->select('cities.id', 'cities.name', 'province.name as province_name')
            ->get();

        return response()->json($cities);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'required|int|max:255',
        ]);

        $city = City::create($data);
        return response()->json($city, 201);
    }

    public function destroy(City $city)
    {
        $city->delete();
        return response()->json(null, 204);
    }
}
