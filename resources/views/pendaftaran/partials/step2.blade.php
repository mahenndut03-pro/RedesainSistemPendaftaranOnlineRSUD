<!-- Step 2: Reservasi (partial) -->
<div id="step-2" class="step hidden">
  <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Step 2: Reservasi</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Cara Bayar -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Cara Bayar <span class="text-red-500">*</span>
      </label>
      <select name="cara_bayar" id="cara_bayar" class="form-control" required onchange="toggleBpjsFields()">
        <option value="">Pilih Cara Bayar</option>
        <option value="UMUM">UMUM</option>
        <option value="BPJS">BPJS</option>
      </select>
    </div>
    <!-- Tanggal Reservasi -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Tanggal Reservasi <span class="text-red-500">*</span>
      </label>
      <input type="date" id="tanggal_reservasi" name="tanggal_reservasi" class="form-control" required min="{{ date('Y-m-d') }}">
    </div>
    <!-- Poliklinik -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Poliklinik Tujuan <span class="text-red-500">*</span>
      </label>
      <select name="poli_id" id="poli_id" class="form-control" required>
        <option value="">Pilih Poliklinik</option>
        {{-- opsi akan diisi via AJAX berdasarkan tanggal reservasi --}}
      </select>
    </div>
    <!-- Dokter -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Dokter <span class="text-red-500">*</span>
      </label>
      <select name="dokter_id" id="dokter_id" class="form-control" required>
        <option value="">Pilih Dokter</option>
      </select>
    </div>
    <!-- BPJS Fields -->
    <div id="bpjs_fields" class="hidden md:col-span-2">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            No Kartu <span class="text-red-500">*</span>
          </label>
          <input type="text" id="no_bpjs" name="no_bpjs" class="form-control">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            No Rujukan <span class="text-red-500">*</span>
          </label>
          <input type="text" id="no_rujukan" name="no_rujukan" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <!-- Buttons -->
  <div class="mt-6 flex justify-between">
    <button type="button"
      onclick="prevStep(2)"
      class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">
      Kembali
    </button>
    <button type="button"
      onclick="nextStep(2)"
      class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">
      Lanjut
    </button>
  </div>
</div>
  <script>
  document.addEventListener('DOMContentLoaded', function(){
    const poliSelect = document.getElementById('poli_id');
    const dokterSelect = document.getElementById('dokter_id');
    const tanggalInput = document.getElementById('tanggal_reservasi');
    if (!poliSelect || !dokterSelect || !tanggalInput) return;

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

    async function loadDoctors(){
      const poli = poliSelect.value;
      dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
      if (!poli) return;
      const tanggal = tanggalInput.value;
      const cara = (document.getElementById('cara_bayar') && document.getElementById('cara_bayar').value) ? document.getElementById('cara_bayar').value : '';
      const url = '{{ url('/pendaftaran/pasien-baru/dokter') }}' + '/' + encodeURIComponent(poli) + '?tanggal=' + encodeURIComponent(tanggal || '') + '&cara_bayar=' + encodeURIComponent(cara || '');
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
    // expose for compatibility with Select2 initializer in main page
    try { window.loadDoctors = loadDoctors; } catch(e) {}
    // when date changes, reload polis and then update doctors for selected poli
    tanggalInput.addEventListener('change', async function(){
      await loadPolisByDate(this.value);
      loadDoctors();
    });
    // when cara bayar changes, refresh doctor list (quota may differ)
    const caraBayarEl = document.getElementById('cara_bayar');
    if (caraBayarEl) {
      caraBayarEl.addEventListener('change', function(){ loadDoctors(); });
    }

    if (tanggalInput.value) loadPolisByDate(tanggalInput.value);
  });
  </script>
