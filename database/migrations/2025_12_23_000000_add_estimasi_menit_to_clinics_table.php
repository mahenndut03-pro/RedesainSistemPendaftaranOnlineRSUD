<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->integer('estimasi_menit')->default(10)->after('pelayanan_aktif');
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('estimasi_menit');
        });
    }
};
