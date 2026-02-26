<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PengambilanPOOController extends Controller
{
    public function index()
    {
        $poos = [
            [
                'id' => 1,
                'name' => 'Restoran Padang Sejahtera',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'contact' => '0812345678',
                'type' => 'Restoran',
                'total_collected' => 320,
            ],
            [
                'id' => 2,
                'name' => 'Warung Ibu Sari',
                'address' => 'Jl. Kemanggisan Raya No. 12, Jakarta Barat',
                'contact' => '0856789012',
                'type' => 'Rumah Tangga',
                'total_collected' => 85,
            ],
            [
                'id' => 3,
                'name' => 'KFC Gading Serpong',
                'address' => 'Summarecon Mall Serpong Lt 2, Tangerang',
                'contact' => '02155123456',
                'type' => 'Restoran',
                'total_collected' => 540,
            ],
            [
                'id' => 4,
                'name' => 'PKK RW 03 Kel. Mangga Besar',
                'address' => 'Jl. Mangga Besar VIII No. 5, Jakarta Barat',
                'contact' => '0878901234',
                'type' => 'UMKM',
                'total_collected' => 45,
            ],
            [
                'id' => 5,
                'name' => 'Hotel Nusantara Jakarta',
                'address' => 'Jl. MH. Thamrin No. 1, Jakarta Pusat',
                'contact' => '02131234567',
                'type' => 'Restoran',
                'total_collected' => 870,
            ],
            [
                'id' => 6,
                'name' => 'Rumah Pak Budi123',
                'address' => 'Jl. Anggrek No. 22, Bekasi',
                'contact' => '0857373647',
                'type' => 'Rumah Tangga',
                'total_collected' => 28,
            ],
        ];

        return Inertia::render('admin/PengambilanPOO', [
            'poos' => $poos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:stations,code',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        Station::create($request->only(['code', 'name', 'city', 'province']));

        Cache::flush();

        return redirect()->route('admin.station');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:stations,code,' . $id,
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        $station = Station::findOrFail($id);
        $station->update($request->only(['code', 'name', 'city', 'province']));

        Cache::flush();

        return redirect()->route('admin.station');
    }

    public function destroy($id)
    {
        $station = Station::findOrFail($id);
        $station->delete();

        Cache::flush();

        return redirect()->route('admin.station');
    }

    public function search(Request $request)
    {
        $query = $request->input('search', '');
        $cacheKey = 'stations:search:' . md5($query);

        $stations = Cache::remember($cacheKey, 3600, function () use ($query) {
            return DB::table('stations')
                ->whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    if ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('city', 'LIKE', "%{$query}%")
                            ->orWhere('code', 'LIKE', "%{$query}%");
                    }
                })
                ->select('id', 'code', 'name', 'city', 'province')
                ->limit(100)
                ->get();
        });

        return response()->json($stations);
    }
}
