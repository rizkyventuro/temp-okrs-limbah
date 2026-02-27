<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POOController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\UCOBatchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('public/Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // POO (Master Data)
    // =====================
    Route::prefix('poos')->name('poos.')->group(function () {
        Route::get('/', [POOController::class, 'index'])->name('index');           // List semua POO + search
        Route::post('/', [POOController::class, 'store'])->name('store');          // Tambah POO baru
        Route::get('/{poo}', [POOController::class, 'show'])->name('show');        // Detail POO
        Route::put('/{poo}', [POOController::class, 'update'])->name('update');    // Edit POO
        Route::delete('/{poo}', [POOController::class, 'destroy'])->name('destroy'); // Hapus POO
    });

    // =====================
    // Pengambilan UCO (Batch)
    // =====================
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/{poo}/create', [UCOBatchController::class, 'create'])->name('create');            // Form catat pengambilan
        Route::post('/', [UCOBatchController::class, 'store'])->name('store');                         // Simpan pengambilan

        Route::get('/{batch}/qr', [UCOBatchController::class, 'generateQR'])->name('qr');             // Halaman berhasil + QR
    });

    // // =====================
    // // Transfer UCO
    // // =====================
    // Route::prefix('transfers')->name('transfers.')->group(function () {
    //     Route::get('/', [UCOTransferController::class, 'index'])->name('index');          // List transfer (sent & received)
    //     Route::post('/', [UCOTransferController::class, 'store'])->name('store');         // Kirim UCO (generate QR transfer)
    //     Route::get('/{transfer}', [UCOTransferController::class, 'show'])->name('show');  // Detail transfer

    //     // Aksi penerima
    //     Route::post('/claim', [UCOTransferController::class, 'claim'])->name('claim');            // Terima via scan QR / kode manual
    //     Route::delete('/{transfer}/cancel', [UCOTransferController::class, 'cancel'])->name('cancel'); // Batalkan transfer (hanya pengirim, status pending)
    // });

    // // =====================
    // // Penjualan / Final Export
    // // =====================
    // Route::prefix('exports')->name('exports.')->group(function () {
    //     Route::get('/', [UCOExportController::class, 'index'])->name('index');           // List semua export
    //     Route::post('/', [UCOExportController::class, 'store'])->name('store');          // Buat draft export
    //     Route::get('/{export}', [UCOExportController::class, 'show'])->name('show');     // Detail export + rantai kepemilikan

    //     // Konfirmasi final (LOCKED setelah ini)
    //     Route::post('/{export}/confirm', [UCOExportController::class, 'confirm'])->name('confirm');

    //     // Dokumen ISCC
    //     Route::get('/{export}/iscc', [UCOExportController::class, 'downloadISCC'])->name('iscc.download');
    // });

    // // =====================
    // // Riwayat Transaksi
    // // =====================
    // Route::prefix('transactions')->name('transactions.')->group(function () {
    //     Route::get('/', [TransactionController::class, 'index'])->name('index');         // Semua riwayat (batch, transfer, export)
    //     Route::get('/custody/{batch}', [TransactionController::class, 'custody'])->name('custody'); // Rantai kepemilikan per batch
    // });

    
    // Route::get('admin/transfer', [StationController::class, 'index'])->name('admin.transfer');
    // Route::post('admin/transfer', [StationController::class, 'store']);
    // Route::put('admin/transfer/{id}', [StationController::class, 'update']);
    // Route::delete('admin/transfer/{id}', [StationController::class, 'destroy']);
});

Route::get('api/stations/search', [StationController::class, 'search'])->name('api.stations.search');

require __DIR__ . '/settings.php';

Route::fallback(function () {
    return Inertia::render('errors/Error404');
});
