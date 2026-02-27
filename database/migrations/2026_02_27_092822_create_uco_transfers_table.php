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
        Schema::create('uco_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('uco_batch_id')
                ->constrained('uco_batches')
                ->onDelete('cascade');

            $table->string('receiver_name');                  // Nama penerima
            $table->string('receiver_company')->nullable();   // Perusahaan / Usaha penerima

            $table->string('transfer_code')->unique();        // Kode transfer (TRF-2026-0001)
            $table->string('transfer_qr_code')->nullable();   // Path QR khusus transfer
            $table->tinyInteger('status')->default(1);        // Lihat keterangan di atas
            $table->timestamp('claimed_at')->nullable();      // Waktu penerima klaim

            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uco_transfers');
    }
};
