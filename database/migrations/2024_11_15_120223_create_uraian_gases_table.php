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
        Schema::create('uraian_gases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kwitansi_id')->constrained('kwitansis')->cascadeOnDelete();
            $table->foreignId('tipe_gas_id')->constrained('tipe_gases')->cascadeOnDelete();
            $table->unsignedBigInteger('kuantitas');
            $table->unsignedBigInteger('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uraian_gases');
    }
};
