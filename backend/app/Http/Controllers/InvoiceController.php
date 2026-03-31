<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('organization');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('total', 'like', "%$search%")
                ->orWhereHas('organization', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        $per_page = (int) $request->input('per_page', 10);

        $invoices = $query->paginate($per_page);

        return response()->json($invoices);
    }

    public function show($id)
    {
        return response()->json(Invoice::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|integer',
            'date' => 'required|date',
            'current_expiry' => 'required|date',
            'new_expiry' => 'required|date',
            'total' => 'required|numeric',
        ]);

        $invoice = Invoice::create($data);
        return response()->json($invoice, 201);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = $request->validate([
            'organization_id' => 'sometimes|integer',
            'date' => 'sometimes|date',
            'current_expiry' => 'sometimes|date',
            'new_expiry' => 'sometimes|date',
            'total' => 'sometimes|numeric',
        ]);

        $invoice->update($data);
        return response()->json($invoice);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json(['message' => 'deleted']);
    }
}
