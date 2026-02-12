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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->enum('cara_bayar', ['UMUM', 'BPJS']);
            $table->unsignedBigInteger('poli_id');
            $table->unsignedBigInteger('dokter_id');
            $table->date('tanggal_reservasi');
            $table->string('no_bpjs')->nullable();
            $table->string('no_rujukan')->nullable();
            $table->string('kode_booking')->unique();
            $table->timestamps();

            $table->foreign('poli_id')->references('id')->on('clinics');
            $table->foreign('dokter_id')->references('id')->on('doctors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
