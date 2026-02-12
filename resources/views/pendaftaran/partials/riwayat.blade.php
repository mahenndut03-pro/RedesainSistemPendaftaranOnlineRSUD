<!-- Riwayat Pendaftaran Modal - Redesigned -->
<div id="riwayatModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
  <div class="relative mx-auto w-full max-w-3xl bg-white dark:bg-[#1e2839] rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#2c3e8f] to-[#1e3a5f] dark:from-teal-600 dark:to-teal-800 px-6 md:px-8 py-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl md:text-3xl font-bold text-white mb-1">Riwayat Pendaftaran</h2>
          <p class="text-blue-100 text-sm">Cari data pendaftaran Anda dengan mudah</p>
        </div>
        <button onclick="closeRiwayatModal()" class="text-white hover:bg-white/20 p-2 rounded-full transition-colors" title="Tutup">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Body -->
    <div class="p-6 md:p-8 max-h-[70vh] overflow-y-auto">
      <!-- Search Section -->
      <div class="mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-100 dark:border-gray-600">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#2c3e8f] dark:text-teal-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            Cari Pendaftaran
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Search Type -->
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Metode Pencarian</label>
              <select id="searchType" class="form-control bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 focus:border-[#2c3e8f] dark:focus:border-teal-400 transition" onchange="toggleSearchInput()">
                <option value="">-- Pilih Metode --</option>
                <option value="no_ktp">No KTP</option>
                <option value="kode_booking">Kode Booking/Reservasi</option>
              </select>
            </div>
            
            <!-- Search Input -->
            <div>
              <label id="searchLabel" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Masukkan Data</label>
              <input 
                type="text" 
                id="searchInput" 
                class="form-control bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 focus:border-[#2c3e8f] dark:focus:border-teal-400 transition" 
                placeholder="Masukkan data pencarian" 
                disabled
              >
            </div>
          </div>

          <!-- Search Button -->
          <button 
            onclick="searchRiwayat()" 
            class="w-full bg-gradient-to-r from-[#2c3e8f] to-[#1e3a5f] hover:from-[#1e3a5f] hover:to-[#142847] dark:from-teal-500 dark:to-teal-700 dark:hover:from-teal-600 dark:hover:to-teal-800 text-white font-semibold py-3 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            Cari Sekarang
          </button>
        </div>
      </div>

      <!-- Results Section -->
      <div id="riwayatResults" class="hidden">
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Hasil Pencarian
          </h3>
          
          <!-- Responsive Table Container -->
          <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <table class="riwayat-result-table">
              <thead>
                <tr>
                  <th>Nama Lengkap</th>
                  <th>Tanggal Lahir</th>
                  <th>Jenis Kelamin</th>
                  <th>Email</th>
                  <th>Poli Tujuan</th>
                  <th>Tanggal Reservasi</th>
                  <th>Dokter</th>
                  <th>Status</th>
                  {{-- <th class="text-center">Action</th> --}}
                </tr>
              </thead>
              <tbody id="riwayatTableBody">
                <!-- Rows akan ditampilkan di sini -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Empty State (shown when no results) -->
      <div id="emptyState" class="text-center py-8">
        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 mb-2">Pilih metode pencarian dan masukkan data Anda</p>
        <p class="text-sm text-gray-400 dark:text-gray-500">Hasil akan ditampilkan di sini</p>
      </div>

      <!-- Not Found Modal -->
      <div id="notFoundModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-60 hidden p-4">
        <div class="relative mx-auto bg-white dark:bg-[#1e2839] rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6 w-full max-w-sm">
          <div class="text-center">
            
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Data Tidak Ditemukan</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Silakan cek kembali data yang Anda masukkan atau gunakan metode pencarian lain.</p>
            <button 
              onclick="closeNotFoundModal()" 
              class="w-full bg-[#2c3e8f] hover:bg-[#1e3a5f] dark:bg-teal-600 dark:hover:bg-teal-700 text-white font-semibold py-2 rounded-lg transition-colors"
            >
              Coba Lagi
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 px-6 md:px-8 py-4 flex justify-end gap-3">
      <button 
        onclick="closeRiwayatModal()" 
        class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors"
      >
        Tutup
      </button>
    </div>
  </div>
</div>

<style>
  /* Custom scrollbar untuk results section */
  #riwayatModal::-webkit-scrollbar {
    width: 8px;
  }
  #riwayatModal::-webkit-scrollbar-track {
    background: transparent;
  }
  #riwayatModal::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
  #riwayatModal::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
  .dark #riwayatModal::-webkit-scrollbar-thumb {
    background: #475569;
  }
  .dark #riwayatModal::-webkit-scrollbar-thumb:hover {
    background: #64748b;
  }

  /* Table styling */
  .riwayat-result-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
  }
  
  .riwayat-result-table thead {
    background: linear-gradient(to right, #f3f4f6, #e5e7eb);
  }
  
  .dark .riwayat-result-table thead {
    background: linear-gradient(to right, #374151, #1f2937);
  }
  
  .riwayat-result-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 2px solid #d1d5db;
    white-space: nowrap;
  }
  
  .dark .riwayat-result-table th {
    color: #e5e7eb;
    border-bottom-color: #4b5563;
  }
  
  .riwayat-result-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
  }
  
  .dark .riwayat-result-table td {
    color: #d1d5db;
    border-bottom-color: #4b5563;
  }
  
  .riwayat-result-table tbody tr {
    transition: background-color 0.2s ease;
  }
  
  .riwayat-result-table tbody tr:hover {
    background-color: #f9fafb;
  }
  
  .dark .riwayat-result-table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }

  /* Status badge styling */
  .status-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
  }

  .status-badge.pending,
  .status-badge.menunggu {
    background-color: #fef3c7;
    color: #92400e;
  }

  .dark .status-badge.pending,
  .dark .status-badge.menunggu {
    background-color: #78350f;
    color: #fcd34d;
  }

  .status-badge.confirmed,
  .status-badge.terkonfirmasi,
  .status-badge.approved {
    background-color: #d1fae5;
    color: #065f46;
  }

  .dark .status-badge.confirmed,
  .dark .status-badge.terkonfirmasi,
  .dark .status-badge.approved {
    background-color: #064e3b;
    color: #6ee7b7;
  }

  .status-badge.cancelled,
  .status-badge.batal {
    background-color: #fee2e2;
    color: #7f1d1d;
  }

  .dark .status-badge.cancelled,
  .dark .status-badge.batal {
    background-color: #7f1d1d;
    color: #fca5a5;
  }

  .status-badge.completed,
  .status-badge.selesai {
    background-color: #bfdbfe;
    color: #1e40af;
  }

  .dark .status-badge.completed,
  .dark .status-badge.selesai {
    background-color: #1e3a8a;
    color: #93c5fd;
  }

  /* Action buttons */
  .action-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
  }

  .action-button-view {
    background-color: #3b82f6;
    color: white;
  }

  .action-button-view:hover {
    background-color: #2563eb;
  }

  .action-button-print {
    background-color: #10b981;
    color: white;
  }

  .action-button-print:hover {
    background-color: #059669;
  }

  .dark .action-button-view {
    background-color: #0ea5e9;
  }

  .dark .action-button-view:hover {
    background-color: #0284c7;
  }

  .dark .action-button-print {
    background-color: #14b8a6;
  }

  .dark .action-button-print:hover {
    background-color: #0d9488;
  }

  /* Responsive table wrapper */
  @media (max-width: 768px) {
    .riwayat-result-table {
      font-size: 0.75rem;
    }

    .riwayat-result-table th,
    .riwayat-result-table td {
      padding: 0.5rem;
    }

    .action-button {
      padding: 0.25rem 0.5rem;
      font-size: 0.65rem;
    }
  }
</style>
