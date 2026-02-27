<?php

namespace App\Http\Controllers;

use App\Models\Poo;
use App\Models\UcoBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UCOBatchController extends Controller
{
    public function create($pooId)
    {
        $poo = Poo::findOrFail($pooId);

        return Inertia::render('admin/poos/CatatPengambilanPOO', [
            'poo' => [
                'id' => $poo->id,
                'name' => $poo->name,
                'address' => $poo->address,
                'type' => $poo->type,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'poo_id' => 'required|exists:poos,id',
            'volume' => 'required|numeric|min:0.1',
            'collection_date' => 'required|date',
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('batch-photos', 'public');
        }

        $batch = DB::transaction(function () use ($request, $photoPath) {
            $batchCode = UcoBatch::generateBatchCode();

            $batch = UcoBatch::create([
                'poo_id' => $request->poo_id,
                'batch_code' => $batchCode,
                'volume' => $request->volume,
                'collection_date' => $request->collection_date,
                'photo' => $photoPath,
                'notes' => $request->notes,
                'status' => UcoBatch::STATUS_AKTIF,
                'created_by' => Auth::id(),
            ]);

            // Update total_volume di POO
            Poo::where('id', $request->poo_id)
                ->increment('total_volume', $request->volume);

            return $batch;
        });

        return redirect()->route('poos.batches.qr', $batch->id);
    }

    public function generateQR($batchId)
    {
        $batch = UcoBatch::with('poo')->findOrFail($batchId);

        // Static QR sementara
        $qrData = urlencode($batch->batch_code);
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$qrData}";

        return Inertia::render('admin/poos/BerhasilPengambilanPOO', [
            'batch' => [
                'id' => $batch->id,
                'code' => $batch->batch_code,
                'poo_name' => $batch->poo->name,
                'volume' => (float) $batch->volume,
                'collection_date' => $batch->collection_date->toDateString(),
                'status' => $batch->status_label,
                'qr_code_url' => $qrCodeUrl,
            ],
        ]);
    }
}
