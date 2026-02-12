@extends('admin.layouts.app')

@section('title', 'Jadwal Dokter')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Jadwal Dokter</h1>
    <a href="{{ route('jadwal.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Jadwal
    </a>
</div>

@if(session('success'))
<div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
    {{ session('success') }}
</div>
@endif

<table class="w-full bg-white rounded shadow text-sm table-auto border-collapse border border-gray-200">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 border-center border-gray-200">No</th>
            <th class="p-3 text-center border border-gray-200">Poli</th>
            <th class="p-3 text-center border border-gray-200">Dokter</th>
            <th class="p-3 border-center border-gray-200">Tanggal</th>
            <th class="p-3 border-center border-gray-200">Jam</th>
            <th class="p-3 text-center border border-gray-200">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($jadwals as $i => $jadwal)
        <tr class="border-t">
            <td class="p-3 text-center border border-gray-200">{{ $i + 1 }}</td>
            <td class="p-3 border border-gray-200">{{ $jadwal->poli->nama_poli ?? '-' }}</td>
            <td class="p-3 border border-gray-200">{{ $jadwal->dokter->nama ?? '-' }}</td>
            <td class="p-3 text-center border border-gray-200">
                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d-m-Y') }}
            </td>
            <td class="p-3 text-center border border-gray-200">
                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
            </td>
            <td class="p-3 text-center space-x-2 border border-gray-200">
                <a href="{{ route('jadwal.edit', $jadwal) }}"
                   class="text-blue-600 hover:underline">
                   Edit
                </a>

                <form action="{{ route('jadwal.destroy', $jadwal) }}"
                      method="POST"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus jadwal ini?')"
                            class="text-red-600 hover:underline">
                        Hapus
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="p-4 text-center text-gray-500 border border-gray-200">
                Data jadwal belum ada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
