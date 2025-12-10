<?php

namespace App\Http\Controllers;

use App\Models\KkLink;
use App\Models\Member;
use Illuminate\Http\Request;

class KkLinkController extends Controller
{
    // Simpan hubungan KK → KK
    public function store(Request $request)
    {
        $request->validate([
            'nokk_main' => 'required',
            'nokk_related' => 'required|different:nokk_main',
        ]);

        // Cek duplikasi (dua arah)
        $exists = KkLink::where(function ($q) use ($request) {
            $q->where('nokk_main', $request->nokk_main)
              ->where('nokk_related', $request->nokk_related);
        })->orWhere(function ($q) use ($request) {
            $q->where('nokk_main', $request->nokk_related)
              ->where('nokk_related', $request->nokk_main);
        })->exists();

        if ($exists) {
            return response()->json(['message' => 'Link KK sudah ada'], 400);
        }

        $kk = KkLink::create([
            'nokk_main' => $request->nokk_main,
            'nokk_related' => $request->nokk_related,
        ]);

        return response()->json([
            'message' => 'KK berhasil disambungkan',
            'data' => $kk
        ], 201);
    }

    // Hapus hubungan
    public function destroy($id)
    {
        $link = KkLink::findOrFail($id);
        $link->delete();

        return response()->json(['message' => 'Link KK dihapus']);
    }

    // Ambil KK cluster (rekursif)
    public function getLinkedKKs($nokk)
    {
        $cluster = $this->collectKKCluster($nokk);

        return response()->json([
            'root' => $nokk,
            'connected_kk' => $cluster
        ]);
    }

    // Ambil semua member dari seluruh KK yang terhubung
    public function getFamilyMembers($nokk)
    {
        // Ambil daftar family_id cluster
        $kkList = $this->collectKKCluster($nokk);

        // Ambil member berdasarkan family_id
        $members = Member::whereIn('family_id', $kkList)->get();

        return response()->json([
            'root_nokk' => $nokk,
            'kk_cluster' => $kkList,
            'members' => $members
        ]);
    }

    // Helper internal: BFS untuk kumpulkan seluruh KK yang saling terhubung
    private function collectKKCluster($start)
    {
        $visited = [];
        $queue = [$start];

        while (!empty($queue)) {
            $current = array_shift($queue);

            if (in_array($current, $visited)) continue;

            $visited[] = $current;

            $links = KkLink::where('nokk_main', $current)
                ->orWhere('nokk_related', $current)
                ->get();

            foreach ($links as $link) {
                $next = $link->nokk_main == $current
                    ? $link->nokk_related
                    : $link->nokk_main;

                if (!in_array($next, $visited)) {
                    $queue[] = $next;
                }
            }
        }

        return $visited;
    }
}
