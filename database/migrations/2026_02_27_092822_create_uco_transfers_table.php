<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * status:
     *   1 = Pending    (QR sudah di-generate, menunggu penerima scan)
     *   2 = Completed  (penerima sudah scan/klaim kepemilikan)
     *   3 = Cancelled
     */
    public function up(): void
    {
        // Schema::create('uco_transfers', function (Blueprint $table) {
        //     $table->uuid('id')->primary();

        //     $table->foreignUuid('uco_batch_id')
        //         ->constrained('uco_batches')
        //         ->onDelete('cascade');

        //     // Pengirim
        //     $table->foreignUuid('sender_id')
        //         ->constrained('users')
        //         ->onDelete('cascade');

        //     // Penerima (nullable: bisa belum terdaftar di sistem saat transfer dibuat)
        //     $table->foreignUuid('receiver_id')
        //         ->nullable()
        //         ->constrained('users')
        //         ->onDelete('set null');

        //     $table->string('receiver_name');                  // Nama pengepul penerima
        //     $table->string('receiver_company')->nullable();   // Perusahaan / Usaha penerima

        //     $table->string('transfer_qr_code')->nullable();   // Path QR khusus transfer
        //     $table->tinyInteger('status')->default(1);        // Lihat keterangan di atas
        //     $table->timestamp('claimed_at')->nullable();      // Waktu penerima klaim

        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('uco_transfers');
    }
};
