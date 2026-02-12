<!-- Step 3: Data Diri (partial) -->
<div id="step-3" class="step hidden">
  <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Step 3: Data Diri</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
      <input type="text" id="telepon" name="telepon" class="form-control" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
      <select name="pendidikan" id="pendidikan" class="form-control" required>
        <option value="">Pilih Pendidikan</option>
        @foreach($pendidikans as $pendidikan)
          <option value="{{ $pendidikan->name ?? $pendidikan->nama }}">{{ $pendidikan->name ?? $pendidikan->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span class="text-red-500">*</span></label>
      <select name="status" id="status" class="form-control" required>
        <option value="">Pilih Status</option>
        @foreach($statuses as $status)
          <option value="{{ $status->name ?? $status->nama }}">{{ $status->name ?? $status->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
      <select name="pekerjaan" id="pekerjaan" class="form-control" required>
        <option value="">Pilih Pekerjaan</option>
        @foreach($pekerjaans as $pekerjaan)
          <option value="{{ $pekerjaan->name ?? $pekerjaan->nama }}">{{ $pekerjaan->name ?? $pekerjaan->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Agama <span class="text-red-500">*</span></label>
      <select name="agama" id="agama" class="form-control" required>
        <option value="">Pilih Agama</option>
        @foreach($agamas as $agamaItem)
          <option value="{{ $agamaItem->name ?? $agamaItem->nama }}">{{ $agamaItem->name ?? $agamaItem->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Golongan Darah <span class="text-red-500">*</span></label>
      <select name="golongan_darah" id="golongan_darah" class="form-control" required>
        <option value="">Pilih Golongan Darah</option>
        @foreach($golonganDarahs as $darah)
          <option value="{{ $darah->name ?? $darah->nama }}">{{ $darah->name ?? $darah->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kewarganegaraan <span class="text-red-500">*</span></label>
      <select name="kewarganegaraan" id="kewarganegaraan" class="form-control" required>
        <option value="">Pilih Kewarganegaraan</option>
        @foreach($kewarganegaraans as $wn)
          <option value="{{ $wn->name ?? $wn->nama }}">{{ $wn->name ?? $wn->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bahasa Keseharian <span class="text-red-500">*</span></label>
      <select name="bahasa_keseharian" id="bahasa_keseharian" class="form-control" required>
        <option value="">Pilih Bahasa Keseharian</option>
        @foreach($bahasaKeseharians as $bahasa)
          <option value="{{ $bahasa->name ?? $bahasa->nama }}">{{ $bahasa->name ?? $bahasa->nama }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Suku <span class="text-red-500">*</span></label>
      <select name="suku" id="suku" class="form-control" required>
        <option value="">Pilih Suku</option>
        @foreach($sukus as $sukuItem)
          <option value="{{ $sukuItem->name ?? $sukuItem->nama }}">{{ $sukuItem->name ?? $sukuItem->nama }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="mt-6 flex justify-between">
    <button type="button" onclick="prevStep(3)" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">Kembali</button>
    <button type="button" onclick="nextStep(3)" class="bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white px-6 py-2 rounded-md transition">Lanjut</button>
  </div>
</div>
