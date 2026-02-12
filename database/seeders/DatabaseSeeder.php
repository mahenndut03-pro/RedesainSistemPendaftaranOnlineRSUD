<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // NOTE: Removed automatic creation of a test user to avoid leaving
        // dummy accounts in production databases. If you need a test user,
        // create it manually or via a dedicated, environment-gated seeder.

        $this->call([
            KlinikSeeder::class,
            DokterSeeder::class,
            JadwalSeeder::class,
            PendidikansTableSeeder::class,
            StatusesTableSeeder::class,
            PekerjaansTableSeeder::class,
            AgamasTableSeeder::class,
            DarahsTableSeeder::class,
            WargasTableSeeder::class,
            BahasasTableSeeder::class,
            SukusTableSeeder::class,
        ]);
    }
}
