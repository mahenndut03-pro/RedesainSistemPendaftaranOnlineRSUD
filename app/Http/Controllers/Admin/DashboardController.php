<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Klinik;
use App\Models\Dokter;
use App\Models\Reservasi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index', [
            'totalPoli' => Klinik::count(),
            'totalDokter' => Dokter::count(),
            'reservasiHariIni' => Reservasi::whereDate('created_at', Carbon::today())->count(),
            'recentReservasi' => Reservasi::with(['pasien','poli','dokter'])->orderBy('created_at','desc')->limit(5)->get(),
        ]);
    }
}
