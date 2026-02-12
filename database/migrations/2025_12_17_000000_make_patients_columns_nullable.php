<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw statements to avoid requiring doctrine/dbal
        DB::statement("ALTER TABLE `patients` MODIFY `no_ktp` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `tempat_lahir` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `tanggal_lahir` DATE NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `jenis_kelamin` ENUM('L','P') NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `pendidikan` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `status` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `pekerjaan` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `agama` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `golongan_darah` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `bahasa` VARCHAR(255) NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `suku` VARCHAR(255) NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to NOT NULL (may fail if rows contain NULLs)
        DB::statement("ALTER TABLE `patients` MODIFY `no_ktp` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `tempat_lahir` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `tanggal_lahir` DATE NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `jenis_kelamin` ENUM('L','P') NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `pendidikan` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `status` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `pekerjaan` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `agama` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `golongan_darah` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `bahasa` VARCHAR(255) NOT NULL;");
        DB::statement("ALTER TABLE `patients` MODIFY `suku` VARCHAR(255) NOT NULL;");
    }
};
