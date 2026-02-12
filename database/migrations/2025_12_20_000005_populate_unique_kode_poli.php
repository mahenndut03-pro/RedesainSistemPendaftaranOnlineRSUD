<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Populate kode_poli for existing clinics if empty, ensure uniqueness
        $existing = [];
        $clinics = DB::table('clinics')->get();

        foreach ($clinics as $c) {
            $name = trim($c->nama_poli ?? '');
            $code = null;

            if (!empty($c->kode_poli)) {
                $code = strtoupper(trim($c->kode_poli));
            } else if (!empty($name)) {
                // create acronym from words
                $words = preg_split('/[^A-Za-z0-9]+/', $name);
                $acr = '';
                foreach ($words as $w) {
                    if ($w === '') continue;
                    $acr .= mb_substr($w, 0, 1);
                    if (mb_strlen($acr) >= 4) break;
                }
                if (mb_strlen($acr) < 2) {
                    $clean = preg_replace('/[^A-Za-z0-9]/', '', $name);
                    $acr = mb_substr($clean, 0, 3);
                }
                $code = strtoupper($acr);
            } else {
                $code = 'P' . $c->id;
            }

            // sanitize and limit length
            $code = preg_replace('/[^A-Za-z0-9]/', '', $code);
            $code = substr($code, 0, 5);

            // ensure uniqueness by appending number if needed
            $base = $code;
            $i = 1;
            while (in_array($code, $existing) || DB::table('clinics')->where('kode_poli', $code)->where('id', '<>', $c->id)->exists()) {
                $code = $base . $i;
                $code = substr($code, 0, 10);
                $i++;
            }

            DB::table('clinics')->where('id', $c->id)->update(['kode_poli' => $code]);
            $existing[] = $code;
        }

        // Add unique index on kode_poli for faster lookups and uniqueness
        Schema::table('clinics', function (Blueprint $table) {
            $table->unique('kode_poli');
        });
    }

    public function down()
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropUnique(['kode_poli']);
        });
    }
};
