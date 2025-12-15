<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use Illuminate\Http\Request;

class AssetLoanController extends Controller
{
    public function index()
    {
        return AssetLoan::with('asset')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_id'             => 'required|exists:assets,id',
            'borrower_name'        => 'required|string|max:150',
            'borrower_type'        => 'required|in:jemaat,komisi,internal',
            'loan_date'            => 'required|date',
            'expected_return_date' => 'required|date',
            'actual_return_date'   => 'nullable|date',
            'status'               => 'required|in:borrowed,returned,late',
            'notes'                => 'nullable|string'
        ]);

        $loan = AssetLoan::create($data);

        return response()->json(['data' => $loan], 201);
    }

    public function show($id)
    {
        $loan = AssetLoan::with('asset')->findOrFail($id);
        return response()->json(['data' => $loan]);
    }

    public function update(Request $request, $id)
    {
        $loan = AssetLoan::findOrFail($id);

        $data = $request->validate([
            'borrower_name'        => 'sometimes|required|string|max:150',
            'borrower_type'        => 'sometimes|required|in:jemaat,komisi,internal',
            'loan_date'            => 'sometimes|required|date',
            'expected_return_date' => 'sometimes|required|date',
            'actual_return_date'   => 'nullable|date',
            'status'               => 'sometimes|required|in:borrowed,returned,late',
            'notes'                => 'nullable|string'
        ]);

        $loan->update($data);

        return response()->json(['data' => $loan]);
    }

    public function destroy($id)
    {
        $loan = AssetLoan::findOrFail($id);
        $loan->delete();

        return response()->json(['message' => 'Loan deleted']);
    }
}
