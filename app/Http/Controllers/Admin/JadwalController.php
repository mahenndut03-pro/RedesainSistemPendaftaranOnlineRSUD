<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Klinik;
use App\Models\Dokter;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['poli', 'dokter'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $polis   = Klinik::orderBy('nama_poli')->get();
        $dokters = Dokter::orderBy('nama')->get();

        return view('admin.jadwal.create', compact('polis', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'poli_id'     => 'required|exists:clinics,id',
            'dokter_id'   => 'required|exists:doctors,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        // Prevent duplicate jadwal for the same doctor on the same date
        if (Jadwal::where('dokter_id', $request->dokter_id)
            ->where('tanggal', $request->tanggal)
            ->exists()
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['dokter_id' => 'Jadwal untuk dokter pada tanggal tersebut sudah ada.']);
        }

        Jadwal::create([
            'poli_id'     => $request->poli_id,
            'dokter_id'   => $request->dokter_id,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        $polis   = Klinik::orderBy('nama_poli')->get();
        $dokters = Dokter::orderBy('nama')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'polis', 'dokters'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'poli_id'     => 'required|exists:clinics,id',
            'dokter_id'   => 'required|exists:doctors,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        // Prevent creating a duplicate when updating (exclude current record)
        if (Jadwal::where('dokter_id', $request->dokter_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $jadwal->id)
            ->exists()
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['dokter_id' => 'Jadwal untuk dokter pada tanggal tersebut sudah ada.']);
        }

        $jadwal->update([
            'poli_id'     => $request->poli_id,
            'dokter_id'   => $request->dokter_id,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
