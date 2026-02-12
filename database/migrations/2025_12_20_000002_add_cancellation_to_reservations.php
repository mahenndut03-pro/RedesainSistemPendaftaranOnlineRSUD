<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add CANCELLED to enum and add cancellation_reason column
        DB::statement("ALTER TABLE reservations MODIFY status ENUM('PENDING','VERIFIED','REJECTED','CANCELLED') NOT NULL DEFAULT 'PENDING'");

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'cancellation_reason')) {
                $table->dropColumn('cancellation_reason');
            }
        });

        // revert enum back to original set (may fail on older MySQL, keep best-effort)
        DB::statement("ALTER TABLE reservations MODIFY status ENUM('PENDING','VERIFIED','REJECTED') NOT NULL DEFAULT 'PENDING'");
    }
};
