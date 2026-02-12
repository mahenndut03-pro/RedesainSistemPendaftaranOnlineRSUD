<!-- Konfirmasi modals (confirmation, approval, captcha, success) -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-16 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-md bg-white dark:bg-[#1e2839] border-gray-200 dark:border-gray-700">
    <h3 class="text-2xl font-bold text-[#2c3e8f] dark:text-white text-center mb-6">
      Konfirmasi Data Pendaftaran
    </h3>
    <div id="confirmationData" class="space-y-5 text-sm text-gray-700 dark:text-gray-300">
      <!-- Data akan dimasukkan via JavaScript -->
    </div>
    <div class="flex justify-center space-x-4 mt-8">
      <button onclick="closeModal()" class="px-5 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
        Tutup
      </button>
      <button onclick="showApprovalModal()" class="px-5 py-2 bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white rounded-md transition">
        Daftarkan
      </button>
    </div>
  </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-[#1e2839] border-gray-200 dark:border-gray-700">
    <div class="mt-3 text-center">
      <h3 class="text-lg font-medium text-[#2c3e8f] dark:text-white mb-4">Persetujuan</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Apakah Anda setuju dengan data yang telah diisi?</p>
      <div class="flex justify-center space-x-4">
        <button onclick="closeApprovalModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Cancel</button>
        <button onclick="showCaptchaModal()" class="px-4 py-2 bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white rounded-md">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Captcha Modal -->
<div id="captchaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-[#1e2839] border-gray-200 dark:border-gray-700">
    <div class="mt-3 text-center">
      <h3 class="text-lg font-medium text-[#2c3e8f] dark:text-white mb-4">Verifikasi Captcha</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Masukkan kode captcha yang terlihat di bawah:</p>
      <div class="mb-4 flex items-center justify-center">
        <div class="captcha-wrapper" style="display:flex;align-items:center;gap:0.5rem;">
          <img id="captchaImage" src="{{ captcha_src() }}" alt="captcha" class="border-2 border-gray-300 dark:border-gray-600 rounded-lg">
          <button type="button" onclick="refreshCaptcha()" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-md text-sm captcha-refresh-btn">Refresh</button>
        </div>
      </div>
      <input type="text" id="captchaInputModal" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 mb-4" placeholder="Masukkan kode captcha">
      <div class="flex justify-center space-x-4">
        <button onclick="closeCaptchaModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Batal</button>
        <button type="button" onclick="submitFormWithCaptcha()" class="px-4 py-2 bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 text-white rounded-md">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal (simplified) -->
<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" data-print-url="">
  <div class="relative top-28 mx-auto p-4 border w-80 shadow rounded-md bg-white dark:bg-[#1e2839] border-gray-200 dark:border-gray-700">
    <div class="text-center">
      <h3 class="text-lg font-semibold text-[#2c3e8f] dark:text-white mb-2">Berhasil</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Pendaftaran berhasil disimpan.</p>
      <p class="text-sm font-medium text-[#2c3e8f] dark:text-teal-400 mb-4" id="bookingCode">Kode Booking: -</p>
      <div class="flex items-center justify-center space-x-3">
        <button id="printButton" onclick="printBooking()" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Cetak Bukti</button>
        <button onclick="closeSuccessModal()" class="px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Tutup</button>
      </div>
    </div>
  </div>
</div>
