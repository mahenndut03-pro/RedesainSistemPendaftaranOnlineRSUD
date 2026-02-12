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
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('nomor_antrian')->nullable()->after('kode_booking');
            $table->time('waktu')->nullable()->after('nomor_antrian');
            $table->string('estimasi_pelayanan')->nullable()->after('waktu');
            $table->text('alasan_kontrol')->nullable()->after('estimasi_pelayanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['nomor_antrian', 'waktu', 'estimasi_pelayanan', 'alasan_kontrol']);
        });
    }
};
