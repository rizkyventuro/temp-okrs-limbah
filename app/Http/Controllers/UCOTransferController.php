<?php

namespace App\Http\Controllers;

use App\Models\UcoBatch;
use App\Models\UcoTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UCOTransferController extends Controller
{
    public function index()
    {
        $batches = UcoBatch::with('poo')
            ->where('status', UcoBatch::STATUS_AKTIF)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($batch) => [
                'id' => $batch->id,
                'code' => $batch->batch_code,
                'poo_name' => $batch->poo->name,
                'volume' => (float) $batch->volume,
                'status' => $batch->status_label,
            ]);

        return Inertia::render('admin/transfer/ListUCO', [
            'batches' => $batches,
        ]);
    }

    public function create()
    {
        $batches = UcoBatch::with('poo')
            ->where('status', UcoBatch::STATUS_AKTIF)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($batch) => [
                'id' => $batch->id,
                'code' => $batch->batch_code,
                'poo_name' => $batch->poo->name,
                'volume' => (float) $batch->volume,
            ]);

        return Inertia::render('admin/transfer/KirimUCO', [
            'batches' => $batches,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:uco_batches,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_company' => 'nullable|string|max:255',
        ]);

        $batch = UcoBatch::findOrFail($request->batch_id);

        if ($batch->status !== UcoBatch::STATUS_AKTIF) {
            return back()->withErrors(['batch_id' => 'Batch ini tidak tersedia untuk transfer.']);
        }

        $transfer = DB::transaction(function () use ($request, $batch) {
            $transferCode = UcoTransfer::generateTransferCode();

            // Static QR sementara
            $qrData = urlencode($transferCode);
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={$qrData}";

            $transfer = UcoTransfer::create([
                'uco_batch_id' => $batch->id,
                'receiver_name' => $request->recipient_name,
                'receiver_company' => $request->recipient_company,
                'transfer_code' => $transferCode,
                'transfer_qr_code' => $qrCodeUrl,
                'status' => UcoTransfer::STATUS_PENDING,
            ]);

            // Update batch status ke In Transfer
            $batch->update(['status' => UcoBatch::STATUS_IN_TRANSFER]);

            return $transfer;
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function show($transferId)
    {
        $transfer = UcoTransfer::with('batch.poo')->findOrFail($transferId);

        return Inertia::render('admin/transfer/BerhasilTransferUCO', [
            'transfer' => [
                'id' => $transfer->id,
                'transfer_code' => $transfer->transfer_code,
                'batch_code' => $transfer->batch->batch_code,
                'poo_name' => $transfer->batch->poo->name,
                'volume' => (float) $transfer->batch->volume,
                'receiver_name' => $transfer->receiver_name,
                'receiver_company' => $transfer->receiver_company,
                'status' => $transfer->status_label,
                'qr_code_url' => $transfer->transfer_qr_code,
                'created_at' => $transfer->created_at->toDateString(),
            ],
        ]);
    }
}
