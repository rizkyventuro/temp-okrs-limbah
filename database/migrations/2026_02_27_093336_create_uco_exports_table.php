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
        // -------------------------
        // Tabel Final Export
        // -------------------------
        Schema::create('uco_exports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('uco_batch_id')
                ->constrained('uco_batches')
                ->onDelete('cascade');

            $table->string('export_code')->unique();
            $table->string('refinery_name');
            $table->decimal('volume', 10, 2);
            $table->decimal('price_per_liter', 10, 2)
                ->nullable();
            $table->decimal('total_value', 15, 2)
                ->nullable();
            $table->date('export_date');
            $table->tinyInteger('status')->default(1);
            $table->string('iscc_document')->nullable();
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // -------------------------
        // Rantai Kepemilikan (Chain of Custody)
        // -------------------------
        Schema::create('uco_custody_chains', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('uco_batch_id')
                ->constrained('uco_batches')
                ->onDelete('cascade');

            $table->string('from_name')->nullable();
            $table->string('from_company')->nullable();
            $table->string('from_location')->nullable();
            $table->string('to_name')->nullable();
            $table->string('to_company')->nullable();
            $table->string('to_location')->nullable();

            /**
             * event_type:
             *   1 = Collection  (pengambilan pertama dari POO)
             *   2 = Transfer    (perpindahan antar pengepul)
             *   3 = Export      (final export ke refinery)
             */
            $table->tinyInteger('event_type');

            $table->uuid('reference_id')->nullable();
            $table->decimal('volume', 10, 2);
            $table->timestamp('event_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uco_custody_chains');
        Schema::dropIfExists('uco_exports');
    }
};
