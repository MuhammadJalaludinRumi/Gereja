<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('provinceRelation')->get();

        $cities = $cities->map(function ($c) {
            return [
                'id'            => $c->id,
                'name'          => $c->name,
                'province'      => $c->province, // ID tetap integer
                'province_name' => $c->provinceRelation ? $c->provinceRelation->name : null,
            ];
        });

        return response()->json($cities);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'province' => 'required|integer',
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
