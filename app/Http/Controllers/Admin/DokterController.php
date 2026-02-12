<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Klinik;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('poli')
            ->orderBy('nama')
            ->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Klinik::orderBy('nama_poli')->get();
        return view('admin.dokter.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'poli_id' => 'required|exists:clinics,id',
            'kuota_umum' => 'nullable|integer|min:0',
            'kuota_bpjs' => 'nullable|integer|min:0',
        ]);

        Dokter::create([
            'nama'    => $request->nama,
            'poli_id' => $request->poli_id,
            'kuota_umum' => (int) ($request->kuota_umum ?? 0),
            'kuota_bpjs' => (int) ($request->kuota_bpjs ?? 0),
        ]);

        return redirect()
            ->route('dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit(Dokter $dokter)
    {
        $polis = Klinik::orderBy('nama_poli')->get();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, Dokter $dokter)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'poli_id' => 'required|exists:clinics,id',
            'kuota_umum' => 'nullable|integer|min:0',
            'kuota_bpjs' => 'nullable|integer|min:0',
        ]);

        $dokter->update([
            'nama'    => $request->nama,
            'poli_id' => $request->poli_id,
            'kuota_umum' => (int) ($request->kuota_umum ?? 0),
            'kuota_bpjs' => (int) ($request->kuota_bpjs ?? 0),
        ]);

        return redirect()
            ->route('dokter.index')
            ->with('success', 'Dokter berhasil diperbarui');
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->delete();

        return redirect()
            ->route('dokter.index')
            ->with('success', 'Dokter berhasil dihapus');
    }
}
