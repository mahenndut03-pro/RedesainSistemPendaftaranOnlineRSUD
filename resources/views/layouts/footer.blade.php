<footer class="bg-[#232F67] text-white mt-16 relative">
  <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-4 gap-6 items-center">
    <div class="md:col-span-1 flex flex-col items-start space-y-3">
      <img src="{{ asset('images/asset_1_new.png') }}" alt="RSUD Bandung Kiwari" class="w-32">
      <p class="text-sm leading-snug">
        Kreatif, Inovatif, Waspada, Aman, <br>
        Responsif, Integritas.
      </p>
    </div>

    <div class="flex flex-col space-y-3">
      <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 24 24">
          <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
          <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
        </svg>
        <span class="text-sm">sekretariat@rsudbandungkiwari.or.id</span>
      </div>

      <div class="flex items-center space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 opacity-80" fill="currentColor" viewBox="0 0 24 24">
          <path d="M6.62 10.79a15.09 15.09 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.11-.21c1.21.49 2.53.76 3.88.76a1 1 0 011 1v3.49a1 1 0 01-1 1A18 18 0 013 5a1 1 0 011-1h3.49a1 1 0 011 1c0 1.35.27 2.67.76 3.88a1.003 1.003 0 01-.21 1.11l-2.42 2.42z" />
        </svg>
        <span class="text-sm">022-86037777</span>
      </div>
    </div>
    
    <div class="flex items-start space-x-2">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 opacity-80 mt-1">
        <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
      </svg>
      <p class="text-sm leading-snug">
        Jl. Raya Kopo No.311, RT.03/RW.05, Situsaeur, <br>
        Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40233
      </p>
    </div>

    <!-- Tombol Modal -->
    <div class="flex justify-center md:justify-end">
      <button id="openUpdateLog"
         class="inline-flex items-center bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg font-medium transition space-x-2 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        <span>INFORMASI UPDATE LOG</span>
      </button>
    </div>
  </div>

  <!-- Modal Update Log -->
  <div id="updateLogModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 rounded-xl shadow-xl p-6 max-w-2xl w-full mx-4 overflow-y-auto max-h-[90vh]">
      <h2 class="text-xl font-semibold mb-4 text-center">Pemberitahuan</h2>
      <div class="text-sm space-y-4 leading-relaxed">
        <div>
          <p class="font-semibold text-gray-700 dark:text-gray-300">#Update tanggal 25/07/2020</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Notifikasi jika kuota sudah habis</li>
            <li>Pasien diharuskan memilih dokter jika sudah pilih poliklinik</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold text-gray-700 dark:text-gray-300">#Update tanggal 01/06/2021</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Penambahan Layanan Poliklinik</li>
            <li>Penambahan Waktu Pelayanan untuk POLI MALAM</li>
            <li>Nomor Kartu BPJS Pasien dan Telepon otomatis tersimpan jika sudah terdaftar</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold text-gray-700 dark:text-gray-300">#Update tanggal 25/11/2022</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Untuk Pasien Anak BPJS diwajibkan membawa Kartu Keluarga, Kartu Berobat/Kartu Pasien.</li>
            <li>Untuk Pasien BPJS selain Anak diwajibkan membawa dokumen rujukan faskes 1.</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold text-gray-700 dark:text-gray-300">#Update tanggal 04/04/2023</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Pendaftaran Online Pasien BPJS dapat dilakukan melalui Aplikasi Mobile JKN</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold text-gray-700 dark:text-gray-300">#Update tanggal 05/05/2023</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Penambahan Fitur Pendaftaran Pasien Baru Umum</li>
            <li>Penyesuaian Pelayanan Poliklinik Pagi, Siang dan Sore</li>
            <li>Seluruh Reservasi wajib Check In melalui Anjungan Rumah Sakit</li>
          </ul>
        </div>
      </div>

      <div class="text-center pt-4">
        <button id="closeUpdateLog" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow-md transition">CLOSE</button>
      </div>
    </div>
  </div>
</footer>

<!-- Script Modal -->
<script>
  const modal = document.getElementById('updateLogModal');
  const openBtn = document.getElementById('openUpdateLog');
  const closeBtn = document.getElementById('closeUpdateLog');

  openBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
  });

  closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  // Tutup modal jika klik di luar konten
  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });
</script>
