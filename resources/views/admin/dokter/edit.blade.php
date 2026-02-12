@extends('admin.layouts.app')

@section('title', 'Edit Dokter')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Dokter</h1>

<form action="{{ route('dokter.update', $dokter) }}" method="POST"
      class="bg-white p-6 rounded shadow max-w-lg">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-2 font-medium">Nama Dokter</label>
        <input type="text"
               name="nama"
               value="{{ old('nama', $dokter->nama) }}"
               class="w-full border rounded p-2"
               required>
        @error('nama')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label class="block mb-2 font-medium">Poli</label>
        <select name="poli_id"
                class="w-full border rounded p-2"
                required>
            @foreach($polis as $poli)
            <option value="{{ $poli->id }}"
                {{ $dokter->poli_id == $poli->id ? 'selected' : '' }}>
                {{ $poli->nama_poli }}
            </option>
            @endforeach
        </select>
        @error('poli_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Kuota Umum</label>
        <input type="number"
               name="kuota_umum"
               min="0"
               value="{{ old('kuota_umum', $dokter->kuota_umum ?? 0) }}"
               class="w-full border rounded p-2">
        @error('kuota_umum')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Kuota BPJS</label>
        <input type="number"
               name="kuota_bpjs"
               min="0"
               value="{{ old('kuota_bpjs', $dokter->kuota_bpjs ?? 0) }}"
               class="w-full border rounded p-2">
        @error('kuota_bpjs')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>
        <a href="{{ route('dokter.index') }}"
           class="bg-gray-300 px-4 py-2 rounded">
           Batal
        </a>
    </div>
</form>
@endsection
    