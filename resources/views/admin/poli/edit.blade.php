@extends('admin.layouts.app')

@section('title', 'Edit Poli')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Poli</h1>

<form action="{{ route('poli.update', $poli) }}" method="POST"
      class="bg-white p-6 rounded shadow max-w-lg">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-2 font-medium">Nama Poli</label>
        <input type="text"
               name="nama_poli"
               value="{{ old('nama_poli', $poli->nama_poli) }}"
               class="w-full border rounded p-2"
               required>
        @error('nama_poli')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Kode Poli (contoh: THT, AOR)</label>
        <input type="text" name="kode_poli" value="{{ old('kode_poli', $poli->kode_poli) }}" class="w-full border rounded p-2" maxlength="10" />
        @error('kode_poli')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="inline-flex items-center">
            <input type="checkbox" name="pelayanan_aktif" value="1" class="form-checkbox" {{ old('pelayanan_aktif', $poli->pelayanan_aktif) ? 'checked' : '' }}>
            <span class="ml-2">Pelayanan aktif</span>
        </label>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Estimasi Pelayanan (menit)</label>
        <input type="number" name="estimasi_menit" value="{{ old('estimasi_menit', $poli->estimasi_menit ?? 10) }}" class="w-full border rounded p-2" min="0" />
        @error('estimasi_menit')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>
        <a href="{{ route('poli.index') }}"
           class="bg-gray-300 px-4 py-2 rounded">
           Batal
        </a>
    </div>
</form>
@endsection
