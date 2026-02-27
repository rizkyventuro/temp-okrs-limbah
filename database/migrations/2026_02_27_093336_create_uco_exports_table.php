<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * uco_exports — Final Export ke Refinery
     *
     * status:
     *   1 = Draft      (belum dikonfirmasi)
     *   2 = Confirmed  (sudah konfirmasi, FINAL LOCKED)
     *   3 = Cancelled
     */
    public function up(): void
    {
        // // -------------------------
        // // Tabel Final Export
        // // -------------------------
        // Schema::create('uco_exports', function (Blueprint $table) {
        //     $table->uuid('id')->primary();

        //     $table->foreignUuid('uco_batch_id')
        //         ->constrained('uco_batches')
        //         ->onDelete('cascade');

        //     $table->foreignUuid('exporter_id')
        //         ->constrained('users')
        //         ->onDelete('cascade');

        //     $table->string('refinery_name');                  // Nama Refinery/Pembeli (PT Refinery UCO Indonesia)
        //     $table->decimal('volume', 10, 2);                 // Volume export (Liter)
        //     $table->decimal('price_per_liter', 10, 2)
        //         ->nullable();                               // Harga per liter
        //     $table->decimal('total_value', 15, 2)
        //         ->nullable();                               // Total nilai transaksi (Rp)
        //     $table->date('export_date');                      // Tanggal export
        //     $table->tinyInteger('status')->default(1);        // Lihat keterangan di atas
        //     $table->string('iscc_document')->nullable();      // Path file ISCC dokumen
        //     $table->timestamp('locked_at')->nullable();       // Waktu FINAL LOCKED

        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // // -------------------------
        // // Rantai Kepemilikan (Chain of Custody)
        // // Mencatat setiap perpindahan kepemilikan batch UCO
        // // -------------------------
        // Schema::create('uco_custody_chains', function (Blueprint $table) {
        //     $table->uuid('id')->primary();

        //     $table->foreignUuid('uco_batch_id')
        //         ->constrained('uco_batches')
        //         ->onDelete('cascade');

        //     // Nullable karena entry pertama tidak ada pengirim (pengumpul awal)
        //     $table->foreignUuid('from_user_id')
        //         ->nullable()
        //         ->constrained('users')
        //         ->onDelete('set null');

        //     $table->foreignUuid('to_user_id')
        //         ->constrained('users')
        //         ->onDelete('cascade');

        //     $table->string('from_company')->nullable();       // Nama perusahaan pengirim
        //     $table->string('from_location')->nullable();      // Lokasi pengirim
        //     $table->string('to_company')->nullable();         // Nama perusahaan penerima
        //     $table->string('to_location')->nullable();        // Lokasi penerima

        //     /**
        //      * event_type:
        //      *   1 = Collection  (pengambilan pertama dari POO)
        //      *   2 = Transfer    (perpindahan antar pengepul)
        //      *   3 = Export      (final export ke refinery)
        //      */
        //     $table->tinyInteger('event_type');

        //     // Referensi ke tabel asal event (polymorphic-style manual)
        //     $table->uuid('reference_id')->nullable();         // ID dari uco_transfers atau uco_exports
        //     $table->decimal('volume', 10, 2);
        //     $table->timestamp('event_at');                    // Waktu kejadian

        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('uco_custody_chains');
        // Schema::dropIfExists('uco_exports');
    }
};
