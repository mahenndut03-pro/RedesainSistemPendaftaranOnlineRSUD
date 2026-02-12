@extends('admin.layouts.app')

@section('title', 'Data Poli')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Data Poli</h1>
    <a href="{{ route('poli.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        + Tambah Poli
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
            <th class="p-3 text-center border border-gray-200">Nama Poli</th>
            <th class="p-3 text-center border border-gray-200">Pelayanan</th>
            <th class="p-3 text-center border border-gray-200">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($polis as $i => $poli)
        <tr class="border-t">
            <td class="p-3 border border-gray-200">{{ $i + 1 }}</td>
            <td class="p-3 border border-gray-200">
                <div class="font-medium">{{ $poli->nama_poli }}</div>
                @if($poli->kode_poli)
                    <div class="text-xs text-gray-500">Kode: {{ $poli->kode_poli }}</div>
                @endif
            </td>
            <td class="p-3 text-center border border-gray-200">
                @if($poli->pelayanan_aktif)
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Aktif</span>
                @else
                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">Tidak aktif</span>
                @endif
            </td>
            <td class="p-3 text-center space-x-2 border border-gray-200">
                <a href="{{ route('poli.edit', $poli) }}"
                   class="text-blue-600 hover:underline">
                   Edit
                </a>

                <form action="{{ route('poli.destroy', $poli) }}"
                      method="POST"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Hapus poli ini?')"
                            class="text-red-600 hover:underline">
                        Hapus
                    </button>
                </form>
                <form action="{{ route('poli.toggle-pelayanan', $poli) }}" method="POST" class="inline">
                    @csrf
                    <button class="ml-2 text-sm px-2 py-1 rounded bg-gray-200 hover:bg-gray-300">Toggle Pelayanan</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="p-4 text-center text-gray-500 border border-gray-200">
                Data poli belum ada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
