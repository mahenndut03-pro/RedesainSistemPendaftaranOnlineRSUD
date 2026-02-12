<!-- Step 4: Alamat (partial) -->
<div id="step-4" class="step hidden">
  <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Step 4: Alamat</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Provinsi <span class="text-red-500">*</span></label>
      <select name="provinsi" id="provinsi" class="form-control" onchange="loadKabupaten();">
        <option value="">Pilih Provinsi</option>>
        <option value="">Pilih Provinsi</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
      <select name="kabupaten" id="kabupaten" class="form-control" onchange="loadKecamatan()">
        <option value="">Pilih Kabupaten/Kota</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kecamatan <span class="text-red-500">*</span></label>
      <select name="kecamatan" id="kecamatan" class="form-control" onchange="loadKelurahan()">
        <option value="">Pilih Kecamatan</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelurahan <span class="text-red-500">*</span></label>
      <select name="kelurahan" id="kelurahan" class="form-control">
        <option value="">Pilih Kelurahan</option>
      </select>
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
      <textarea id="alamat" name="alamat" rows="3" class="form-control"></textarea>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RT <span class="text-red-500">*</span></label>
      <input type="text" id="rt" name="rt" class="form-control">
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RW <span class="text-red-500">*</span></label>
      <input type="text" id="rw" name="rw" class="form-control">
    </div>
  </div>
  <div class="mt-6 flex justify-between">
    <button type="button" onclick="prevStep(4)" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">Kembali</button>
    <button type="button" onclick="showConfirmationModal()" class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">Kirim</button>
  </div>
</div>
