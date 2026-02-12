<!-- Step 1: Akun (partial) -->
<div id="step-1" class="step">
  <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Step 1: Akun</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
      <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" required autofocus>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
      <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
      <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
      <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
        <option value="">Pilih Jenis Kelamin</option>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No KTP <span class="text-gray-400">(wajib jika umur &ge; 18)</span></label>
      <input type="text" id="no_ktp" name="no_ktp" class="form-control" inputmode="numeric">
    </div>
  </div>
   <div class="flex justify-end mb-4">
    <button type="button" onclick="nextStep(1)" class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">Lanjut</button>
  </div>
</div>
