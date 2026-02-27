<?php

namespace App\Http\Controllers;

use App\Models\Poo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class POOController extends Controller
{
    public function index()
    {
        $poos = Poo::orderBy('created_at', 'desc')
            ->get()
            ->map(fn($poo) => [
                'id' => $poo->id,
                'name' => $poo->name,
                'address' => $poo->address,
                'contact' => $poo->contact,
                'type' => $poo->type,
                'total_collected' => $poo->total_collected,
            ]);

        return Inertia::render('admin/poos/PengambilanPOO', [
            'poos' => $poos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'nullable|string|max:50',
            'type' => 'required|in:Restoran,UMKM,Rumah Tangga',
        ]);

        Poo::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'business_type' => Poo::getBusinessTypeValue($request->type),
            'created_by' => Auth::id(),
        ]);

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact' => 'nullable|string|max:50',
            'type' => 'required|in:Restoran,UMKM,Rumah Tangga',
        ]);

        $poo = Poo::findOrFail($id);
        $poo->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'business_type' => Poo::getBusinessTypeValue($request->type),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $poo = Poo::findOrFail($id);
        $poo->update(['deleted_by' => Auth::id()]);
        $poo->delete();

        return redirect()->back();
    }
}
