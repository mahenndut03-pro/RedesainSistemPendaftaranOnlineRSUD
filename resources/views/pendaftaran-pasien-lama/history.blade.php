@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-300 min-h-screen">
  <div class="max-w-6xl mx-auto px-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow p-6 md:p-8 transition-colors duration-300 relative">
      <a href="#" onclick="window.history.back(); return false;"
        class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
        KEMBALI
      </a>
      <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Riwayat Pendaftaran Online</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Lihat status pendaftaran.</p>
      </div>
      <!-- Kontrol -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
          <label class="text-sm text-gray-600 dark:text-gray-400">Tampilkan</label>
          <select id="entriesSelect" class="border rounded px-3 py-1 text-sm bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="25">25</option>
          </select>
          <span class="text-sm text-gray-500 dark:text-gray-400">entri</span>
        </div>
        <div class="flex items-center gap-2 ml-auto">
          <input id="searchInput" type="text" placeholder="Cari..." class="border rounded px-3 py-2 w-64 text-sm bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" />
        </div>
      </div>
      <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg">
        <table id="historyTable" class="w-full bg-white dark:bg-gray-800">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Kode Reservasi</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Nomor Antrian</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Hari</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Waktu</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Estimasi Pelayanan</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Poliklinik</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Dokter</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Alasan</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200">Status</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-200">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-sm divide-y divide-gray-200 dark:divide-gray-700" id="historyTbody">
            @forelse($reservations ?? [] as $r)
            <tr data-reservasi-id="{{ $r->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
              @php $s = strtoupper($r->status ?? 'PENDING'); @endphp
              <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $r->tanggal_reservasi }}</td>
              <td class="px-4 py-3">
                <span class="inline-block bg-blue-600 text-white px-3 py-1 rounded text-sm">{{ $r->kode_booking }}</span>
              </td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $r->nomor_antrian ?? '-' }}</td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($r->tanggal_reservasi)->locale('id')->translatedFormat('l') ?? '-' }}</td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300 waktu-cell">
                {{ $r->waktu_label ?? '-' }}
              </td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300 estimasi-cell">
                @if($r->estimasi_waktu)
                  {{ $r->estimasi_waktu }}
                @else
                  -
                @endif
              </td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ optional($r->poli)->nama_poli ?? $r->poli_id }}</td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ optional($r->dokter)->nama ?? $r->dokter_id }}</td>
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $r->cancellation_reason ?? $r->alasan_kontrol ?? '-' }}</td>
              <td class="px-4 py-3 status-cell">
                @php $s = strtoupper($r->status ?? 'PENDING'); @endphp
                @if($s === 'VERIFIED')
                  <span class="inline-flex items-center px-3 py-1 rounded text-sm bg-green-50 text-green-800">Terverifikasi</span>
                @elseif($s === 'REJECTED')
                  <span class="inline-flex items-center px-3 py-1 rounded text-sm bg-red-50 text-red-800">Ditolak</span>
                @elseif($s === 'CANCELLED' || $s === 'BATAL' || $s === 'DIBATALKAN')
                  <span class="inline-flex items-center px-3 py-1 rounded text-sm bg-gray-50 text-gray-800">Dibatalkan</span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded text-sm bg-yellow-50 text-yellow-800">Menunggu</span>
                @endif
              </td>
              <td class="px-4 py-3 aksi-cell">
                <div class="flex gap-2">
                  @if($s === 'VERIFIED')
                    <div class="flex gap-2">
                      <a href="{{ route('pendaftaran-pasien-lama.print', $r->id) }}" target="_blank" class="inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium transition">Unduh</a>
                    </div>
                  @elseif(in_array($s, ['REJECTED','CANCELLED']))
                    <span class="w-full inline-block text-center text-sm text-gray-500">-</span>
                  @else
                    <form action="{{ route('pendaftaran-pasien-lama.cancel', $r->id) }}" method="POST" class="cancel-form">
                      @csrf
                      <input type="hidden" name="alasan_batal" class="alasan-input" />
                      <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium transition">Batalkan</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada riwayat pendaftaran.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div id="noResults" class="hidden mt-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada data yang cocok.</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  (function(){
    const searchInput = document.getElementById('searchInput');
    const entriesSelect = document.getElementById('entriesSelect');
    const tbody = document.getElementById('historyTbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const noResults = document.getElementById('noResults');

    function applyFilter(){
      const q = searchInput.value.trim().toLowerCase();
      let visibleCount = 0;
      const max = parseInt(entriesSelect.value || '10', 10);

      rows.forEach(r => {
        const text = r.textContent.trim().toLowerCase();
        const matched = q === '' || text.indexOf(q) !== -1;
        if(matched && visibleCount < max){
          r.style.display = '';
          visibleCount++;
        } else {
          r.style.display = 'none';
        }
      });

      noResults.classList.toggle('hidden', visibleCount > 0);
    }

    searchInput.addEventListener('input', applyFilter);
    entriesSelect.addEventListener('change', applyFilter);

    // initial
    applyFilter();
  })();
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Modal-based cancellation (similar to admin reject)
  let activeCancelForm = null;
  const modal = document.createElement('div');
  modal.id = 'cancelModal';
  modal.innerHTML = `
    <div id="cancelModalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-md w-full max-w-lg mx-4 shadow-lg">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
          <h3 class="font-semibold text-gray-800 dark:text-gray-100">Masukkan alasan pembatalan</h3>
        </div>
        <div class="p-4">
          <textarea id="cancelReason" rows="5" class="w-full border rounded p-2 bg-white dark:bg-[#243447] text-gray-800 dark:text-gray-100" placeholder="Tuliskan alasan pembatalan..."></textarea>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex gap-2 justify-end">
          <button id="cancelModalClose" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Batal</button>
          <button id="cancelModalConfirm" class="px-4 py-2 rounded bg-red-600 text-white">Kirim & Batalkan</button>
        </div>
      </div>
    </div>`;
  document.body.appendChild(modal);

  const overlay = document.getElementById('cancelModalOverlay');
  const reasonEl = document.getElementById('cancelReason');
  const closeBtn = document.getElementById('cancelModalClose');
  const confirmBtn = document.getElementById('cancelModalConfirm');

  function openCancelModal(form) {
    activeCancelForm = form;
    if (reasonEl) reasonEl.value = '';
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    reasonEl && reasonEl.focus();
  }

  function closeCancelModal() {
    activeCancelForm = null;
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
  }

  closeBtn.addEventListener('click', function(){ closeCancelModal(); });
  overlay.addEventListener('click', function(e){ if (e.target === overlay) closeCancelModal(); });

  confirmBtn.addEventListener('click', function(e){
    const v = reasonEl.value && reasonEl.value.trim();
    if (!v) {
      alert('Alasan pembatalan wajib diisi.');
      reasonEl.focus();
      return;
    }
    if (!activeCancelForm) { closeCancelModal(); return; }
    const input = activeCancelForm.querySelector('.alasan-input');
    if (input) input.value = v;
    activeCancelForm.submit();
  });

  // Attach handlers to each cancel form button to open modal
  document.querySelectorAll('.cancel-form').forEach(function(f){
    f.addEventListener('submit', function(e){
      e.preventDefault();
      openCancelModal(f);
    });
  });
});
</script>
<script>
// Polling: check for updates every 5 seconds and update rows inline
;(function(){
  const endpoint = "{{ route('pendaftaran-pasien-lama.history.json') }}";
  async function fetchUpdates(){
    try {
      const res = await fetch(endpoint, { credentials: 'same-origin' });
      if(!res.ok) return;
      const data = await res.json();
      if(!Array.isArray(data)) return;
      data.forEach(item => {
        const tr = document.querySelector('tr[data-reservasi-id="' + item.id + '"]');
        if(!tr) return;

        // waktu (show label if available, prefer formatted estimasi time)
        const waktuTd = tr.querySelector('.waktu-cell');
        if(waktuTd){
          if(item.status === 'VERIFIED'){
            if(item.waktu_label) {
              waktuTd.textContent = item.waktu_label;
            } else if(item.jam) {
              waktuTd.textContent = item.jam;
            } else {
              waktuTd.textContent = '-';
            }
          } else {
            waktuTd.textContent = '-';
          }
        }

        // estimasi (show only HH:MM time when available)
        const estTd = tr.querySelector('.estimasi-cell');
        if(estTd){
          if(item.status === 'VERIFIED'){
            if(item.estimasi_waktu) {
              estTd.textContent = item.estimasi_waktu;
            } else {
              estTd.textContent = '-';
            }
          } else {
            estTd.textContent = '-';
          }
        }

        // status badge
        const statusTd = tr.querySelector('.status-cell');
        if(statusTd){
          let html = '';
          if(item.status === 'VERIFIED') html = '<span class="inline-flex items-center px-3 py-1 rounded text-sm bg-green-50 text-green-800">Terverifikasi</span>';
          else if(item.status === 'REJECTED') html = '<span class="inline-flex items-center px-3 py-1 rounded text-sm bg-red-50 text-red-800">Ditolak</span>';
          else if(['CANCELLED','BATAL','DIBATALKAN'].includes(item.status)) html = '<span class="inline-flex items-center px-3 py-1 rounded text-sm bg-gray-50 text-gray-800">Dibatalkan</span>';
          else html = '<span class="inline-flex items-center px-3 py-1 rounded text-sm bg-yellow-50 text-yellow-800">Menunggu</span>';
          statusTd.innerHTML = html;
        }

        // aksi: if now verified, replace cancel form with unduh link
        const aksiTd = tr.querySelector('.aksi-cell');
        if(aksiTd){
          if(item.status === 'VERIFIED'){
            aksiTd.innerHTML = '<div class="flex gap-2"><div class="flex gap-2"><a href="/pendaftaran-pasien-lama/reservasi/' + item.id + '/print" target="_blank" class="inline-block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium transition">Unduh</a></div></div>';
          } else if(['REJECTED','CANCELLED'].includes(item.status)){
            aksiTd.innerHTML = '<span class="w-full inline-block text-center text-sm text-gray-500">-</span>';
          }
        }
      });
    } catch (e) {
      // ignore network errors silently
    }
  }

  // initial fetch and then interval
  fetchUpdates();
  setInterval(fetchUpdates, 5000);
})();
</script>
@endpush
@endsection
