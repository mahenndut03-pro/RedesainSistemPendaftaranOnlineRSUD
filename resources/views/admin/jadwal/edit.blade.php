@extends('admin.layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Jadwal Dokter</h1>

<form action="{{ route('jadwal.update', $jadwal) }}" method="POST"
      class="bg-white p-6 rounded shadow max-w-xl">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block mb-2 font-medium">Poli</label>
        <select name="poli_id" class="w-full border rounded p-2" required>
            @foreach($polis as $poli)
            <option value="{{ $poli->id }}"
                {{ $jadwal->poli_id == $poli->id ? 'selected' : '' }}>
                {{ $poli->nama_poli }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Dokter</label>
        <select name="dokter_id" class="w-full border rounded p-2" required>
            @foreach($dokters as $dokter)
            <option value="{{ $dokter->id }}"
                {{ $jadwal->dokter_id == $dokter->id ? 'selected' : '' }}>
                {{ $dokter->nama }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Tanggal</label>
        <input type="date" name="tanggal"
               value="{{ $jadwal->tanggal }}"
               class="w-full border rounded p-2" required>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block mb-2 font-medium">Jam Mulai</label>
            <input type="time" name="jam_mulai"
                   value="{{ $jadwal->jam_mulai }}"
                   class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Jam Selesai</label>
            <input type="time" name="jam_selesai"
                   value="{{ $jadwal->jam_selesai }}"
                   class="w-full border rounded p-2" required>
        </div>
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
        </button>
        <a href="{{ route('jadwal.index') }}"
           class="bg-gray-300 px-4 py-2 rounded">
           Batal
        </a>
    </div>
</form>
@endsection
