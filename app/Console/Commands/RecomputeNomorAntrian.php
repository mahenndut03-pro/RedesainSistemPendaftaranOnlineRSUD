<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use App\Models\Klinik;

class RecomputeNomorAntrian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:recompute-antrian {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recompute nomor_antrian for all reservations grouped per poli + date using poli-derived prefixes';

    public function handle()
    {
        $dry = $this->option('dry-run');

        $this->info('Starting recompute of nomor_antrian...');

        // Get all distinct poli+date groups where poli and date exist
        $groups = Reservasi::select('poli_id', 'tanggal_reservasi')
            ->whereNotNull('poli_id')
            ->whereNotNull('tanggal_reservasi')
            ->groupBy('poli_id', 'tanggal_reservasi')
            ->orderBy('tanggal_reservasi')
            ->get();

        $bar = $this->output->createProgressBar($groups->count());
        $bar->start();

        foreach ($groups as $g) {
            $poliId = $g->poli_id;
            $date = $g->tanggal_reservasi;

            $poli = Klinik::find($poliId);
            $prefix = 'P' . ($poliId);
            if ($poli) {
                if (!empty($poli->kode_poli)) {
                    $prefix = strtoupper(trim($poli->kode_poli));
                } else {
                    $name = trim($poli->nama_poli ?? '');
                    $clean = preg_replace('/\b(poliklinik|poliklinik|poli|klinik|unit)\b/iu', ' ', $name);
                    $clean = preg_replace('/\s+/', ' ', trim($clean));
                    $parts = preg_split('/\s+/', $clean);
                    $letters = [];
                    foreach ($parts as $p) {
                        if (empty($p)) continue;
                        $letters[] = mb_substr($p, 0, 1);
                        if (count($letters) >= 3) break;
                    }
                    $acronym = mb_strtoupper(implode('', $letters));
                    if (!empty($acronym)) {
                        $prefix = $acronym;
                    } else {
                        $trimmed = preg_replace('/\s+/', '', $name);
                        $first = mb_substr($trimmed, 0, 1);
                        if (!empty($first)) {
                            $prefix = mb_strtoupper($first);
                        }
                    }
                }
            }

            // Get reservations for this group, order by created_at to keep existing relative order
            $reservasis = Reservasi::where('poli_id', $poliId)
                ->whereDate('tanggal_reservasi', $date)
                ->orderBy('created_at', 'asc')
                ->get();

            $i = 1;
            foreach ($reservasis as $r) {
                $new = $prefix . '-' . sprintf('%02d', $i);
                if ($dry) {
                    $this->line("[DRY] {$r->id} -> {$new}");
                } else {
                    $r->nomor_antrian = $new;
                    $r->save();
                }
                $i++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info('');
        $this->info('Recompute completed' . ($dry ? ' (dry-run)' : ''));
        return 0;
    }
}
