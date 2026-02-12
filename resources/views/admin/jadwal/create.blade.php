@extends('admin.layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Jadwal Dokter</h1>

<form action="{{ route('jadwal.store') }}" method="POST"
      class="bg-white p-6 rounded shadow max-w-xl">
    @csrf

    <div class="mb-4">
        <label class="block mb-2 font-medium">Poli</label>
        <select id="poliSelect" name="poli_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih Poli --</option>
            @foreach($polis as $poli)
            <option value="{{ $poli->id }}"
                {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                {{ $poli->nama_poli }}
            </option>
            @endforeach
        </select>
        @error('poli_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Dokter</label>
        <select id="dokterSelect" name="dokter_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih Dokter --</option>
        </select>
        @error('dokter_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Tanggal</label>
        <input type="date" name="tanggal"
               value="{{ old('tanggal') }}"
               class="w-full border rounded p-2" required>
        @error('tanggal')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block mb-2 font-medium">Jam Mulai</label>
            <input type="time" name="jam_mulai"
                   value="{{ old('jam_mulai') }}"
                   class="w-full border rounded p-2" required>
            @error('jam_mulai')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block mb-2 font-medium">Jam Selesai</label>
            <input type="time" name="jam_selesai"
                   value="{{ old('jam_selesai') }}"
                   class="w-full border rounded p-2" required>
            @error('jam_selesai')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan
        </button>
        <a href="{{ route('jadwal.index') }}"
           class="bg-gray-300 px-4 py-2 rounded">
           Batal
        </a>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const poliSelect = document.getElementById('poliSelect');
    const dokterSelect = document.getElementById('dokterSelect');
    const baseUrl = "{{ url('/pendaftaran/pasien-baru/dokter') }}";
    const oldDokter = "{{ old('dokter_id') ?? '' }}";

    function setOptions(dokters) {
        dokterSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
        dokters.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.nama;
            if (oldDokter && oldDokter == d.id) opt.selected = true;
            dokterSelect.appendChild(opt);
        });
    }

    async function fetchDokters(poliId) {
        if (!poliId) {
            // reset to initial full list if poli not selected
            // keep existing server-rendered options
            return;
        }
        try {
            const res = await fetch(baseUrl + '/' + poliId);
            if (!res.ok) return;
            const data = await res.json();
            setOptions(data);
        } catch (e) {
            console.error('Failed to fetch dokters', e);
        }
    }

    poliSelect.addEventListener('change', function () {
        fetchDokters(this.value);
    });

    // If there's a preselected poli (old input), fetch its doctors to ensure list matches
    if (poliSelect.value) fetchDokters(poliSelect.value);
});
</script>
@endsection
