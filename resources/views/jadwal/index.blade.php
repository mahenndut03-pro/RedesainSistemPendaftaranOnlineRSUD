@extends('layouts.app')

@section('title', 'Jadwal Pelayanan Poliklinik')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-10 px-4">
  <!-- HEADER -->
  <div class="text-center mb-4">
    <h1 class="font-bold text-center text-[#2c3e8f] dark:text-white transition-colors duration-300 text-3xl">
      Jadwal Pelayanan Poliklinik
    </h1>
    <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm">
      Periksa jadwal dokter sebelum melakukan reservasi
    </p>
  </div>
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- CALENDAR -->
    <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 md:col-span-3">
      <!-- SEARCH -->
      <div class="flex justify-center mb-3"> 
        <input id="searchInput"
          type="text"
          placeholder="Cari Poliklinik Yang Dituju..."
          class="w-full sm:w-96 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
      </div>
      <div id="calendarSpinner" class="flex items-center justify-center p-6">
        <div class="text-sm animate-pulse">Memuat kalender...</div>
      </div>
      <div id="calendar"></div>
    </div>
    <!-- TODAY LIST -->
    <div class="bg-white dark:bg-[#0b1220] rounded-xl shadow overflow-hidden">
      <div class="bg-[#2C3E8F] text-white text-center py-3 font-semibold">
        Rincian Jadwal
      </div>
      <div id="todayList" class="divide-y text-sm p-2 bg-white dark:bg-[#071025]"></div>
    </div>
  </div>
</div>

<!-- MODAL -->
<div id="detailModal"
  role="dialog" aria-modal="true"
  class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-80"></div>
</div>
@endsection

@push('scripts') 
<style> 
/* FullCalendar general tweaks */ 
.fc { background: transparent; color: inherit; } .fc-toolbar-title { color: inherit !important; } .fc .fc-daygrid-event { white-space: normal !important; overflow: visible !important; text-overflow: clip !important; font-size: 0.85rem; padding: 4px 6px; border-radius: 6px; } .fc .fc-daygrid-day.fc-day-today { background-color: rgba(156,163,175,0.06) !important; } .fc .fc-button { padding: 6px 10px; } #calendar { min-height: 520px; } #todayList { max-height: 640px; overflow-y: auto; }

/* Default (light) event title color */ 
.fc .fc-daygrid-event-dot + .fc-event-title, .fc .fc-event-title { color: #0f172a !important; } 

/* Column header and day number contrast (light mode uses black) */ 
.fc .fc-col-header-cell-cushion { color: #000000 !important; font-weight:600; } .fc .fc-daygrid-day-number { color: #000000 !important; }

/* Subtle separators to match dark background */ 
.fc .fc-daygrid-day-frame { border-color: rgba(255,255,255,0.04); } .fc .fc-scrollgrid-section { border-color: rgba(255,255,255,0.04); }

/* Dark mode overrides when parent has dark class */ 
.dark .fc { color: #e6eef8; } .dark .fc .fc-col-header-cell-cushion { color: #cfe6ff !important; } .dark .fc .fc-daygrid-day-number { color: #cfe6ff !important; } 
</style>

<style> 
/* Stronger selectors to ensure header/day numbers are black in light mode */ 
.fc .fc-col-header thead .fc-col-header-cell .fc-col-header-cell-cushion, .fc .fc-col-header-cell-cushion { color: #000000 !important; } .fc .fc-daygrid-day-top .fc-daygrid-day-number, .fc .fc-daygrid-day-number { color: #000000 !important; } 

/* If parent page uses dark class, restore light colors */ 
.dark .fc .fc-col-header-cell-cushion, .dark .fc .fc-daygrid-day-number { color: #cfe6ff !important; } 
</style>

</style>

 <style> 
/* Ensure modal text remains dark on white background even when parent uses dark mode */ 
#detailModal .modal-content, #detailModal > div { color: #1f2937 !important; } .dark #detailModal .modal-content, .dark #detailModal > div { color: #1f2937 !important; } 
</style> 

<style> 
/* Make calendar summary readable: dark text in light mode, white in dark mode */
 .fc-jadwal-summary { color: #0f172a !important; } .fc-jadwal-summary .font-bold, .fc-jadwal-summary .font-semibold, .fc-jadwal-summary .text-sm, .fc-jadwal-summary .text-xs { color: #0f172a !important; } 

/* Override any gray utility classes inside the injected summary (light mode) */ 
.fc-jadwal-summary .text-gray-600, .fc-jadwal-summary .text-gray-500, .fc-jadwal-summary .text-gray-400 { color: #6b7280 !important; } 

/* Dark-mode overrides for injected summary and events */
 .dark .fc .fc-daygrid-event-dot + .fc-event-title, .dark .fc .fc-event-title { color: #ffffff !important; } .dark .fc-jadwal-summary { color: #ffffff !important; } .dark .fc-jadwal-summary .font-bold, .dark .fc-jadwal-summary .font-semibold, .dark .fc-jadwal-summary .text-sm, .dark .fc-jadwal-summary .text-xs { color: #ffffff !important; } .dark .fc-jadwal-summary .text-gray-600, .dark .fc-jadwal-summary .text-gray-500, .dark .fc-jadwal-summary .text-gray-400 { color: #ffffff !important; } 

</style> <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
// GLOBAL VARIABLES

let summaryMap = {};
let calendar = null;
let searchQuery = '';

// GLOBAL HELPERS (FIX UTAMA)
function pad(n){ return n < 10 ? '0' + n : '' + n; }

function formatLocalDate(d){
  return d.getFullYear() + '-' + pad(d.getMonth()+1) + '-' + pad(d.getDate());
}

  //API SUMMARY
async function loadSummary(start, end) {
  const url = new URL("{{ route('api.jadwal.summary') }}", location.origin);
  if (start) url.searchParams.set('start', start);
  if (end) url.searchParams.set('end', end);
  const res = await fetch(url);
  summaryMap = await res.json();
}

// DAY CELL CONTENT
function dayCellContent(dateStr) {
  const s = summaryMap[dateStr];
  if (!s) return '<div class="p-1 text-center text-xs text-gray-400">-</div>';

  // Filter polis based on search query
  const filteredPolis = searchQuery
    ? s.polis.filter(p => p.name.toLowerCase().includes(searchQuery))
    : s.polis;

  if (filteredPolis.length === 0) return '<div class="p-1 text-center text-xs text-gray-400">-</div>';

  // Render poli names only — remove numeric counts from the date cell
  let html = '';
  html += '<div class="mt-1 text-xs text-gray-600">';
  html += filteredPolis.slice(0,4)
    .map(p => `● ${p.name} (${p.doctor_count})`)
    .join('<br>');
  html += '</div>';
  return html;
}

// INIT CALENDAR

document.addEventListener('DOMContentLoaded', async function () {

  const spinner = document.getElementById('calendarSpinner');

  const today = new Date();
  const start = formatLocalDate(new Date(today.getFullYear(), today.getMonth(), 1));
  const end = formatLocalDate(new Date(today.getFullYear(), today.getMonth()+1, 0));

  await loadSummary(start, end);
  spinner.classList.add('hidden');

  calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
    initialView: 'dayGridMonth',
    height: 'auto',

    dayCellDidMount(arg) {
      const dateStr = formatLocalDate(arg.date);
      const top = arg.el.querySelector('.fc-daygrid-day-top');
      if (top) {
        top.insertAdjacentHTML(
          'afterend',
          `<div class="fc-jadwal-summary p-1">${dayCellContent(dateStr)}</div>`
        );
      }
      // populate the right-side 'Rincian Jadwal' instead of opening a modal
      arg.el.addEventListener('click', () => populateTodayList(dateStr));
    },

    datesSet: async function(info){
      const s = formatLocalDate(info.view.activeStart);
      const e = new Date(info.view.activeEnd);
      e.setDate(e.getDate() - 1);
      await loadSummary(s, formatLocalDate(e));
      calendar.render();
    }
  });

  calendar.render();
  // show initial hint in the right panel
  showHintPanel();
  // Ensure modal is a child of <body> to avoid stacking-context issues
  const detailModal = document.getElementById('detailModal');
  try {
    if (detailModal && detailModal.parentElement !== document.body) {
      document.body.appendChild(detailModal);
    }
  } catch (err) {
    // ignore if DOM operation fails
  }

  detailModal.addEventListener('click', function(e){
    if (e.target === this) closeModal();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
  });
});
   
//TODAY PANEL (INI YANG FIX)
async function renderTodayPanel() {
  const box = document.getElementById('todayList');
  box.innerHTML = '';

  const today = formatLocalDate(new Date());

  if (!summaryMap[today]) {
    box.innerHTML =
      '<div class="p-3 text-center text-sm text-gray-500">Tidak ada jadwal hari ini</div>';
    return;
  }

  summaryMap[today].polis.forEach(p => {
    box.innerHTML += `
      <div class="p-3">
        <button onclick="openDatePanelAndSelectPoli('${today}', ${p.id})"
          class="w-full text-left">
          <div class="font-semibold">${p.name}</div>
          <div class="text-xs text-gray-500">${p.doctor_count} dokter</div>
        </button>
      </div>`;
  });
}

// Populate Rincian Jadwal panel for a specific date
function populateTodayList(dateStr) {
  const box = document.getElementById('todayList');
  const s = summaryMap[dateStr] || { polis: [] };
  box.innerHTML = '';

  // header
  box.innerHTML += `<div class="p-3"><div class="font-semibold">${formatDate(dateStr)}</div></div>`;

  if (!s.polis.length) {
    box.innerHTML += '<div class="p-3 text-sm text-gray-500">Tidak ada jadwal pada tanggal ini</div>';
    return;
  }
  // render each poli as an expandable card; doctors load into the details area for that poli
  s.polis.forEach(p => {
    const poliHtml = `
      <div class="p-3 border-b" id="poliCard-${p.id}">
        <button onclick="togglePoli(${p.id}, '${dateStr}')" class="w-full text-left flex justify-between items-center">
          <div class="font-semibold">${p.name}</div>
          <div class="text-xs text-gray-500">${p.doctor_count} dokter</div>
        </button>
        <div id="poliDetails-${p.id}" class="mt-2 hidden"></div>
      </div>`;
    box.insertAdjacentHTML('beforeend', poliHtml);
  });
}

// Toggle poli details: if hidden, load doctors; if visible, hide
function togglePoli(poliId, dateStr) {
  const details = document.getElementById('poliDetails-' + poliId);
  if (!details) return;
  if (!details.classList.contains('hidden')) {
    details.classList.add('hidden');
    return;
  }
  // show loading state
  details.classList.remove('hidden');
  details.innerHTML = '<div class="p-2 text-sm text-gray-500">Memuat dokter...</div>';
  selectPoli(poliId, dateStr);
}

// Show a helpful hint when the right-side panel is empty
function showHintPanel() {
  const box = document.getElementById('todayList');
  if (!box) return;
  box.innerHTML = `
    <div class="p-6 text-center text-sm text-gray-500">
      Klik tanggal di kalender untuk melihat rincian jadwal.
    </div>`;
}

//MODAL HANDLERS
function openDatePanel(dateStr) {
  const s = summaryMap[dateStr] || { polis: [] };
  const modal = document.getElementById('detailModal');
  modal.querySelector('div').innerHTML =
    `<div class="font-semibold mb-2">${formatDate(dateStr)}</div>` +
    (s.polis.length
      ? s.polis.map(p =>
        `<button class="w-full text-left p-2 border-b"
          onclick="selectPoli(${p.id}, '${dateStr}')">
          ${p.name} (${p.doctor_count} dokter)
        </button>`).join('')
      : '<div class="p-2 text-sm text-gray-500">Tidak ada poli</div>');

  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

async function selectPoli(poliId, dateStr) {
  // Prefer rendering into the right-side panel; fall back to modal if panel not present
  const panel = document.getElementById('todayList');
  let contentContainer = null;

  // If a dedicated poli details container exists for this poli, use it
  const poliDetails = document.getElementById('poliDetails-' + poliId);
  if (poliDetails) {
    contentContainer = poliDetails;
  } else if (panel) {
    // fallback to a shared todayDetails area (shouldn't normally be used now)
    let details = document.getElementById('todayDetails');
    if (!details) {
      details = document.createElement('div');
      details.id = 'todayDetails';
      panel.appendChild(details);
    }
    contentContainer = details;
  } else {
    const modal = document.getElementById('detailModal');
    contentContainer = modal.querySelector('div');
  }

  contentContainer.innerHTML = 'Memuat dokter...';

  const res = await fetch(`{{ url('') }}/api/jadwal/date/${dateStr}/poli/${poliId}`);
  const list = await res.json();

  contentContainer.innerHTML = list.length
    ? list.map(d => {
      const remUmum = (d.kuota_umum ?? 0) - (d.reserved_umum ?? 0);
      const remBpjs = (d.kuota_bpjs ?? 0) - (d.reserved_bpjs ?? 0);
      return `
      <div class="p-2 border-b">
        <div class="font-semibold text-sm">${d.nama}</div>
        <div class="text-xs text-gray-500 mt-1">${d.jam_mulai} - ${d.jam_selesai}</div>
        <div class="mt-2 text-xs text-gray-600">
          ${d.kuota_umum ? `<div>Kuota Umum: <strong>${d.kuota_umum}</strong> (sisa <strong>${remUmum}</strong>)</div>` : ''}
          ${d.kuota_bpjs ? `<div>Kuota BPJS: <strong>${d.kuota_bpjs}</strong> (sisa <strong>${remBpjs}</strong>)</div>` : ''}
        </div>
      </div>`;
    }).join('')
    : '<div class="text-sm text-gray-500">Tidak ada dokter</div>';
}

function closeModal(){
  const modal = document.getElementById('detailModal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}

function formatDate(d){
  return new Date(d).toLocaleDateString('id-ID',{
    weekday:'long', year:'numeric', month:'long', day:'numeric'
  });
}
   //SEARCH
document.getElementById('searchInput').addEventListener('input', function(){
  searchQuery = this.value.toLowerCase().trim();
  // Update existing calendar summaries
  document.querySelectorAll('.fc-jadwal-summary').forEach(summary => {
    const dayCell = summary.closest('.fc-daygrid-day');
    const dateStr = dayCell.getAttribute('data-date');
    summary.innerHTML = dayCellContent(dateStr);
  });
});

function openDatePanelAndSelectPoli(dateStr, poliId){
  // Populate the right-side details panel and load the dokter list
  populateTodayList(dateStr);
  selectPoli(poliId, dateStr);
}
</script>
@endpush
