<?php

namespace App\Http\Controllers;

use App\Models\EconomyHistory;

class EconomyHistoryController extends Controller
{
    public function index($id)
    {
        $history = EconomyHistory::where('economy_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $history
        ]);
    }
}
