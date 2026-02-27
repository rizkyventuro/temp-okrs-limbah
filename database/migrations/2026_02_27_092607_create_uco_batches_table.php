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
     *   1 = Aktif         (baru dikumpulkan, siap transfer/export)
     *   2 = In Transfer   (sedang dalam proses transfer, menunggu konfirmasi)
     *   3 = Transferred   (sudah berpindah kepemilikan)
     *   4 = Final Export  (sudah dieksport ke refinery, LOCKED)
     *   5 = Expired
     */
    public function up(): void
    {
        Schema::create('uco_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key ke poos (UUID) dan users (bigInteger)
            $table->foreignUuid('poo_id')->constrained('poos')->onDelete('cascade');
            $table->unsignedBigInteger('collector_id');       // Pengumpul pertama (tidak berubah)
            $table->unsignedBigInteger('current_owner_id');   // Pemilik saat ini (bisa berubah via transfer)

            $table->string('batch_code')->unique();           // UCO-2026-0666
            $table->decimal('volume', 10, 2);                 // Volume (Liter)
            $table->date('collection_date');                  // Tanggal Pengambilan
            $table->string('photo')->nullable();              // Foto saat pengambilan
            $table->text('notes')->nullable();                // Catatan tambahan
            $table->string('qr_code')->nullable();            // Path QR code batch
            $table->tinyInteger('status')->default(1);        // Lihat keterangan di atas
            $table->decimal('estimated_value', 15, 2)
                ->nullable();                               // Nilai estimasi (Rp)

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints untuk users (bigInteger)
            $table->foreign('collector_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('current_owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uco_batches', function (Blueprint $table) {
            $table->dropForeign(['poo_id']);
            $table->dropForeign(['collector_id']);
            $table->dropForeign(['current_owner_id']);
        });

        Schema::dropIfExists('uco_batches');
    }
};
