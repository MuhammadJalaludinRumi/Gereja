<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use Illuminate\Http\Request;

class FormulirController extends Controller
{
    // Tidak pakai middleware auth
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Formulir::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_pengguna' => 'required|email',
            'pesan' => 'required'
        ]);

        $formulir = Formulir::create($validated);

        return response()->json([
            'message' => 'Formulir berhasil dibuat',
            'data' => $formulir
        ], 201);
    }

    public function show($id)
    {
        return Formulir::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $formulir = Formulir::findOrFail($id);

        $formulir->update($request->only('email_pengguna', 'pesan'));

        return response()->json([
            'message' => 'Formulir berhasil diupdate',
            'data' => $formulir
        ]);
    }

    public function destroy($id)
    {
        Formulir::destroy($id);

        return response()->json([
            'message' => 'Formulir dihapus'
        ]);
    }
}
