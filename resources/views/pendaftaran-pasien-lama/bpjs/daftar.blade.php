@extends('layouts.app')

@section('content')
<div class="py-8 transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Header Judul -->
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i>
                Form Pendaftaran Online
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                Jika Nama dokter tidak tampil setelah pilih Poliklinik, maka ada kemungkinan <span class="font-semibold">KUOTA SUDAH HABIS</span> atau tidak ada Dokter yang praktek pada tanggal tersebut.
            </p>
                @if($errors->any())
                    <div class="mb-4">
                        <div class="text-sm text-red-700 bg-red-50 border border-red-100 p-3 rounded">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4">
                        <div class="text-sm text-green-700 bg-green-50 border border-green-100 p-3 rounded">{{ session('success') }}</div>
                    </div>
                @endif
        </div>

        <!-- Card Form -->
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 relative">
            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>
            <form action="{{ route('bpjs.daftar.post') }}" method="POST" data-validate-on-submit="true" novalidate>

                @csrf

                <!-- Nama & KTP (hidden when user logged in) -->
                @if(!isset($currentPatient))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nama lengkap" value="{{ old('nama_lengkap', $currentPatient->nama_lengkap ?? '') }}">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">No. KTP</label>
                        <input type="text" id="no_ktp" name="no_ktp" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nomor KTP (opsional)" value="{{ old('no_ktp', $currentPatient->no_ktp ?? '') }}">
                    </div>
                </div>
                @else
                <input type="hidden" name="nama_lengkap" value="{{ old('nama_lengkap', $currentPatient->nama_lengkap ?? '') }}">
                <input type="hidden" name="no_ktp" value="{{ old('no_ktp', $currentPatient->no_ktp ?? '') }}">
                @endif

                <!-- Tanggal Reservasi -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Tanggal Reservasi <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" value="{{ old('tanggal_reservasi', $reservasi->tanggal_reservasi ?? request('tanggal_rujukan') ?? '') }}" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <!-- Alasan Kontrol -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Alasan Kontrol</label>
                    <textarea id="alasan_kontrol" name="alasan_kontrol" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" rows="3">{{ old('alasan_kontrol', $reservasi->alasan_kontrol ?? '') }}</textarea>
                </div>

                <!-- Poliklinik & Dokter -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Pilih Poliklinik <span class="text-red-500">*</span></label>
                        <select id="poli_id" name="poli_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih Poliklinik</option>
                            @foreach($clinics ?? [] as $c)
                                <option value="{{ $c->id }}" {{ old('poli_id', $reservasi->poli_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Pilih Dokter <span class="text-red-500">*</span></label>
                        <select id="dokter_id" name="dokter_id" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="">Pilih Dokter</option>
                            @foreach($doctors ?? [] as $d)
                                <option value="{{ $d->id }}" data-poli="{{ $d->poli_id }}" {{ old('dokter_id', $reservasi->dokter_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Pembayaran <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" value="BPJS" readonly>
                </div>

                <!-- Nomor Telepon & No BPJS (hide phone input if logged in) -->
                @if(!isset($currentPatient))
                <div class="mb-8">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                    <input type="text" id="telepon" name="telepon" required value="{{ old('telepon','') }}" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Masukkan Nomor Telepon Aktif">
                </div>
                @else
                <input type="hidden" id="telepon" name="telepon" value="{{ old('telepon', $currentPatient->telepon ?? '') }}">
                @endif

                <div class="mb-6">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200 mb-2">No. BPJS <span class="text-red-500">*</span></label>
                    <input type="text" id="no_bpjs" name="no_bpjs" required inputmode="numeric" class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Nomor BPJS" value="{{ old('no_bpjs', $reservasi->no_bpjs ?? $currentPatient->no_bpjs ?? '') }}">
                </div>
                <!-- keep hidden rujukan fields so incoming rujukan params are submitted (no visible UI) -->
                <input type="hidden" name="no_rujukan" id="no_rujukan" value="{{ old('no_rujukan', $reservasi->no_rujukan ?? request('no_rujukan')) }}">
                <input type="hidden" name="tanggal_rujukan" id="tanggal_rujukan" value="{{ old('tanggal_rujukan', $reservasi->tanggal_rujukan ?? request('tanggal_rujukan')) }}">
                <input type="hidden" name="waktu" id="waktu" value="{{ old('waktu', $reservasi->waktu ?? request('waktu')) }}">
                <input type="hidden" name="kode_booking" value="{{ $reservasi->kode_booking ?? '' }}">
                <!-- Tombol Daftarkan -->
                <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-lg shadow text-center transition flex items-center justify-center gap-2">
                    DAFTARKAN
                </button>
                @include('pendaftaran.validation-partial')
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const poliSelect = document.getElementById('poli_id');
    const dokterSelect = document.getElementById('dokter_id');
    if (!poliSelect || !dokterSelect) return;

    async function loadDoctors(){
        const poli = poliSelect.value;
        dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
        if (!poli) return; // only load when poli selected
        const tanggal = document.getElementById('tanggal_reservasi') ? document.getElementById('tanggal_reservasi').value : null;
        if (!tanggal) { dokterSelect.innerHTML = '<option value="">Pilih tanggal terlebih dahulu</option>'; return; }
        const url = '{{ url('/pendaftaran/pasien-baru/dokter') }}' + '/' + encodeURIComponent(poli) + '?tanggal=' + encodeURIComponent(tanggal) + '&cara_bayar=BPJS';
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('HTTP '+res.status);
            const data = await res.json();
            data.forEach(d => {
                const el = document.createElement('option');
                el.value = d.id;
                el.textContent = d.nama;
                dokterSelect.appendChild(el);
            });
        } catch (e) {
            console.error('Gagal memuat dokter', e);
        }
    }

    poliSelect.addEventListener('change', loadDoctors);
    const tanggalInput = document.getElementById('tanggal_reservasi');
    async function loadPolisByDate(date){
        poliSelect.innerHTML = '<option value="">Pilih Poliklinik</option>';
        dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
        if (!date) return;
        const url = '{{ url('/api/jadwal/date') }}' + '/' + encodeURIComponent(date) + '/polis';
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error('HTTP '+res.status);
            const data = await res.json();
            data.forEach(c => {
                const el = document.createElement('option');
                el.value = c.id;
                el.textContent = c.nama_poli;
                poliSelect.appendChild(el);
            });
        } catch (e) {
            console.error('Gagal memuat poli berdasarkan tanggal', e);
        }
    }

    if (tanggalInput) {
        tanggalInput.addEventListener('change', function(){
            loadPolisByDate(this.value).then(function(){
                // refresh doctors after polis loaded (match step2)
                loadDoctors();
            });
        });
        if (tanggalInput.value) loadPolisByDate(tanggalInput.value).then(function(){ loadDoctors(); });
    }
});
</script>
<script>
// Client-side validation for BPJS form (mirrors UMUM checks)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-validate-on-submit]');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        // find fields
        const tanggal = form.querySelector('#tanggal_reservasi');
        const poli = form.querySelector('#poli_id');
        const dokter = form.querySelector('#dokter_id');
        const telepon = form.querySelector('#telepon');
        const noBpjs = form.querySelector('#no_bpjs');

        let firstInvalid = null;

        function showFieldError(el, msg) {
            el.classList.add('border-red-500');
            if (!el._errEl) {
                const d = document.createElement('div');
                d.className = 'text-red-600 text-sm mt-1';
                d.textContent = msg;
                el.parentNode.appendChild(d);
                el._errEl = d;
            } else {
                el._errEl.textContent = msg;
            }
        }

        function resetFieldState(el) {
            el.classList.remove('border-red-500');
            if (el._errEl) { el._errEl.remove(); el._errEl = null; }
        }

        [tanggal, poli, dokter, telepon, noBpjs].forEach(f => { try { if (f) resetFieldState(f); } catch(e){} });

        if (tanggal && (!tanggal.value || tanggal.value.trim() === '')) { showFieldError(tanggal, 'Tanggal Reservasi wajib diisi'); if (!firstInvalid) firstInvalid = tanggal; }
        if (poli && (!poli.value || poli.value.trim() === '')) { showFieldError(poli, 'Poliklinik wajib dipilih'); if (!firstInvalid) firstInvalid = poli; }
        if (dokter && (!dokter.value || dokter.value.trim() === '')) { showFieldError(dokter, 'Dokter wajib dipilih'); if (!firstInvalid) firstInvalid = dokter; }
        if (telepon) {
            const t = telepon.value ? telepon.value.trim() : '';
            if (!t) { showFieldError(telepon, 'Nomor Telepon wajib diisi'); if (!firstInvalid) firstInvalid = telepon; }
            else if (!/^[0-9+\-\s()]+$/.test(t) || t.replace(/\D/g,'').length < 10) { showFieldError(telepon, 'Format nomor telepon tidak valid'); if (!firstInvalid) firstInvalid = telepon; }
        }

        if (noBpjs) {
            const b = noBpjs.value ? noBpjs.value.trim() : '';
            if (!b) { showFieldError(noBpjs, 'No BPJS wajib diisi'); if (!firstInvalid) firstInvalid = noBpjs; }
            else if (!/^\d+$/.test(b)) { showFieldError(noBpjs, 'No BPJS harus berupa angka'); if (!firstInvalid) firstInvalid = noBpjs; }
            else if (b.length < 13) { showFieldError(noBpjs, 'No BPJS minimal 13 digit'); if (!firstInvalid) firstInvalid = noBpjs; }
        }

        if (firstInvalid) {
            e.preventDefault();
            try { firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstInvalid.focus(); } catch(e){}
            return false;
        }

        return true;
    });
});
</script>
@endpush