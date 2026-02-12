<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Klinik;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Klinik::orderBy('nama_poli')->get();
        return view('admin.poli.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'kode_poli' => 'nullable|string|max:10',
            'estimasi_menit' => 'nullable|integer|min:0',
        ]);

        Klinik::create([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => $request->kode_poli,
            'pelayanan_aktif' => $request->has('pelayanan_aktif') ? (bool)$request->pelayanan_aktif : true,
            'estimasi_menit' => $request->estimasi_menit ?? 10,
        ]);

        return redirect()
            ->route('poli.index')
            ->with('success', 'Poli berhasil ditambahkan');
    }

    public function edit(Klinik $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, Klinik $poli)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'kode_poli' => 'nullable|string|max:10',
            'estimasi_menit' => 'nullable|integer|min:0',
        ]);

        $poli->update([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => $request->kode_poli,
            'pelayanan_aktif' => $request->has('pelayanan_aktif') ? (bool)$request->pelayanan_aktif : $poli->pelayanan_aktif,
            'estimasi_menit' => $request->has('estimasi_menit') ? (int)$request->estimasi_menit : $poli->estimasi_menit,
        ]);

        return redirect()
            ->route('poli.index')
            ->with('success', 'Poli berhasil diperbarui');
    }

    public function destroy(Klinik $poli)
    {
        $poli->delete();

        return redirect()
            ->route('poli.index')
            ->with('success', 'Poli berhasil dihapus');
    }

    // Toggle pelayanan aktif/non-aktif via admin quick action
    public function togglePelayanan(Klinik $poli)
    {
        $poli->pelayanan_aktif = !$poli->pelayanan_aktif;
        $poli->save();

        return redirect()->route('poli.index')->with('success', 'Status pelayanan diperbarui.');
    }
}
