<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');                           // Nama POO
            $table->text('address');                          // Alamat lengkap
            $table->string('contact')->nullable();            // No. HP / Kontak
            $table->tinyInteger('business_type');             // 1=Restoran, 2=UMKM, 3=Rumah Tangga
            $table->decimal('total_volume', 10, 2)
                ->default(0);                               // Akumulasi total liter terkumpul

            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poos');
    }
};
