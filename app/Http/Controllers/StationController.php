<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StationController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Station', [
            'stations' => Station::all(),
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
                ->where(function($q) use ($query) {
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
