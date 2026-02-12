@extends('admin.layouts.app')

@section('title', 'Data Dokter')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Data Dokter</h1>
    <a href="{{ route('dokter.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Dokter
    </a>
</div>

@if(session('success'))
<div class="mb-4 bg-green-100 text-green-800 p-3 rounded">
    {{ session('success') }}
</div>
@endif

<table class="w-full bg-white rounded shadow table-auto border-collapse border border-gray-200">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-center border border-gray-200">No</th>
            <th class="p-3 text-center border border-gray-200">Nama Dokter</th>
            <th class="p-3 text-center border border-gray-200">Poli</th>
            <th class="p-3 text-center border border-gray-200">Kuota Umum</th>
            <th class="p-3 text-center border border-gray-200">Kuota BPJS</th>
            <th class="p-3 text-center border border-gray-200">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dokters as $i => $dokter)
        <tr class="border-t">
            <td class="p-3 border border-gray-200">{{ $i + 1 }}</td>
            <td class="p-3 border border-gray-200">{{ $dokter->nama }}</td>
            <td class="p-3 border border-gray-200">{{ $dokter->poli->nama_poli ?? '-' }}</td>
            <td class="p-3 border border-gray-200">{{ $dokter->kuota_umum ?? 0 }}</td>
            <td class="p-3 border border-gray-200">{{ $dokter->kuota_bpjs ?? 0 }}</td>
            <td class="p-3 text-center space-x-2 border border-gray-200">
                <a href="{{ route('dokter.edit', $dokter) }}"
                   class="text-blue-600 hover:underline">Edit</a>

                <form action="{{ route('dokter.destroy', $dokter) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus dokter ini?')" class="text-red-600 hover:underline">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="p-4 text-center text-gray-500 border border-gray-200">
                Data dokter belum ada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
