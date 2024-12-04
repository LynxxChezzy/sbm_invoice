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
        Schema::create('saldo_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kwitansi_id')->constrained('kwitansis')->cascadeOnDelete();
            $table->foreignId('tipe_transaksi_id')->constrained('tipe_transaksis')->cascadeOnDelete();
            $table->string('deskripsi', 50);
            $table->unsignedBigInteger('nilai_saldo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_customers');
    }
};
