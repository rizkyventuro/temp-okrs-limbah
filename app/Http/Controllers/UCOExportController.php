<?php

namespace App\Http\Controllers;

use App\Models\UcoBatch;
use App\Models\UcoExport;
use App\Models\UcoTransfer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UCOExportController extends Controller
{
    /**
     * Halaman list batch siap export + riwayat export
     */
    public function index()
    {
        // Batch yang sudah di-transfer (siap export)
        $readyBatches = UcoBatch::with('poo')
            ->where('status', UcoBatch::STATUS_TRANSFERRED)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($batch) => [
                'id' => $batch->id,
                'code' => $batch->batch_code,
                'poo_name' => $batch->poo->name,
                'collection_date' => $batch->collection_date->toDateString(),
                'volume' => (float) $batch->volume,
                'nilai' => (float) ($batch->estimated_value ?? 0),
                'status' => $batch->status_label,
            ]);

        // Riwayat export yang sudah confirmed (FINAL LOCKED)
        $history = UcoExport::with('batch.poo')
            ->where('status', UcoExport::STATUS_CONFIRMED)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($export) => [
                'id' => $export->id,
                'code' => $export->export_code,
                'poo_name' => $export->batch->poo->name,
                'volume' => (float) $export->volume,
                'exported_at' => $export->export_date->toDateString(),
                'iscc_document_path' => $export->iscc_document,
            ]);

        return Inertia::render('admin/exports/ListExportUCO', [
            'readyBatches' => $readyBatches,
            'history' => $history,
        ]);
    }

    /**
     * Halaman konfirmasi sebelum export
     */
    public function confirmation($batchId)
    {
        $batch = UcoBatch::with('poo')->findOrFail($batchId);

        // Build rantai kepemilikan dari data yang ada
        $ownerships = [];

        // 1. POO asal (pemilik pertama)
        $ownerships[] = [
            'id' => 1,
            'role' => 'poo',
            'name' => $batch->poo->name,
            'company' => $batch->poo->name,
            'location' => $batch->poo->address ?? '-',
            'volume' => (float) $batch->volume,
            'owned_at' => $batch->collection_date->toDateString(),
            'is_current' => false,
        ];

        // 2. Transfer (pengepul yang menerima)
        $transfer = UcoTransfer::where('uco_batch_id', $batch->id)
            ->where('status', UcoTransfer::STATUS_COMPLETED)
            ->latest()
            ->first();

        if ($transfer) {
            $ownerships[] = [
                'id' => 2,
                'role' => 'pengepul',
                'name' => $transfer->receiver_name,
                'company' => $transfer->receiver_company ?? $transfer->receiver_name,
                'location' => $transfer->receiver_company ?? '-',
                'volume' => (float) $batch->volume,
                'owned_at' => $transfer->claimed_at?->toDateString() ?? $transfer->created_at->toDateString(),
                'is_current' => true,
            ];
            // POO bukan lagi pemilik saat ini jika sudah di-transfer
            $ownerships[0]['is_current'] = false;
        } else {
            // Jika belum ada transfer, POO masih pemilik
            $ownerships[0]['is_current'] = true;
        }

        // Daftar refinery (bisa dibuat tabel tersendiri, untuk sekarang hardcoded)
        $refineries = [
            ['id' => 1, 'name' => 'PT Pertamina Refinery'],
            ['id' => 2, 'name' => 'PT Bio Energi Nusantara'],
            ['id' => 3, 'name' => 'Global Green Fuel Ltd'],
        ];

        return Inertia::render('admin/exports/KonfirmasiExportUCO', [
            'batch' => [
                'id' => $batch->id,
                'code' => $batch->batch_code,
                'poo_name' => $batch->poo->name,
                'volume' => (float) $batch->volume,
                'collection_date' => $batch->collection_date->toDateString(),
            ],
            'ownerships' => $ownerships,
            'refineries' => $refineries,
        ]);
    }

    /**
     * Proses generate ISCC dokumen dan lock batch
     */
    public function generate(Request $request, $batchId)
    {
        $request->validate([
            'refinery_name' => 'required|string|max:255',
        ]);

        $batch = UcoBatch::with('poo')->findOrFail($batchId);

        if ($batch->status !== UcoBatch::STATUS_TRANSFERRED) {
            return back()->withErrors(['batch_id' => 'Batch ini tidak tersedia untuk export.']);
        }

        $export = DB::transaction(function () use ($request, $batch) {
            $exportCode = UcoExport::generateExportCode();

            $export = UcoExport::create([
                'uco_batch_id' => $batch->id,
                'export_code' => $exportCode,
                'refinery_name' => $request->refinery_name,
                'volume' => $batch->volume,
                'export_date' => now()->toDateString(),
                'status' => UcoExport::STATUS_CONFIRMED,
                'locked_at' => now(),
            ]);

            // Lock batch - set status ke FINAL_EXPORT
            $batch->update([
                'status' => UcoBatch::STATUS_FINAL_EXPORT,
            ]);

            return $export;
        });

        return redirect()->route('exports.success', $export->id);
    }

    /**
     * Halaman sukses export (FINAL LOCKED)
     */
    public function success($exportId)
    {
        $export = UcoExport::with('batch.poo')->findOrFail($exportId);

        return Inertia::render('admin/exports/ExportSuccessUco', [
            'export' => [
                'id' => $export->id,
                'batch_code' => $export->batch->batch_code,
                'poo_name' => $export->batch->poo->name,
                'volume' => (float) $export->volume,
                'exported_at' => $export->export_date->toDateString(),
                'refinery_name' => $export->refinery_name,
                'iscc' => $this->buildIsccData($export),
            ],
        ]);
    }

    /**
     * Preview ISCC document (HTML untuk iframe)
     */
    public function previewIscc($id)
    {
        $export = UcoExport::with('batch.poo')->findOrFail($id);

        return view('pdf.iscc-document', [
            'iscc' => $this->buildIsccData($export),
        ]);
    }

    /**
     * Download ISCC document sebagai PDF
     */
    public function download($exportId)
    {
        $export = UcoExport::with('batch.poo')->findOrFail($exportId);

        $iscc = $this->buildIsccData($export);
        $pdf = Pdf::loadView('pdf.iscc-document', ['iscc' => $iscc])
            ->setPaper('a4', 'portrait');

        $filename = 'ISCC-' . $export->export_code . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Build data ISCC dari export record
     */
    private function buildIsccData(UcoExport $export): array
    {
        return [
            'poo_name' => $export->batch->poo->name,
            'poo_street' => $export->batch->poo->address ?? '-',
            'poo_city' => '-',
            'poo_country' => 'Indonesia',
            'poo_phone' => $export->batch->poo->contact ?? '-',
            'uco_amount' => number_format((float) $export->volume, 0, ',', '.') . ' Litres',
            'recipient' => $export->refinery_name,
            'signatory' => $export->batch->poo->name . ' – Manager',
            'place_date' => 'Indonesia, ' . $export->export_date->format('d F Y'),
        ];
    }
}
