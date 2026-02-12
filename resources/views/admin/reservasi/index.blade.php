@extends('admin.layouts.app')

@section('title', 'Data Reservasi')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold">Data Reservasi</h1>
    <div class="text-sm text-gray-500">Kelola reservasi pasien</div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <form method="GET" action="{{ route('admin.reservasi.index') }}">
        <div class="px-4 py-3 sm:px-6 border-b dark:border-gray-700 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 w-full max-w-md">
                <input name="q" value="{{ old('q', $q ?? request('q')) }}" type="text" placeholder="Cari kode, nama atau poli..." class="w-full px-3 py-2 rounded border bg-gray-50 dark:bg-gray-900 text-sm focus:outline-none" />
                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Cari</button>
                <a href="{{ route('admin.reservasi.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-sm rounded">Reset</a>
            </div>
            <div class="text-xs text-gray-500">Menampilkan {{ is_object($reservasis) && method_exists($reservasis, 'total') ? $reservasis->total() : $reservasis->count() }} hasil</div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border-collapse border border-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-900 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left border border-gray-200">No</th>
                    <th class="px-4 py-3 border border-gray-200">Kode</th>
                    <th class="px-4 py-3 border border-gray-200">Antrian</th>
                    <th class="px-4 py-3 border border-gray-200">Pasien</th>
                    <th class="px-4 py-3 border border-gray-200">Poli</th>
                    <th class="px-4 py-3 border border-gray-200">Dokter</th>
                    <th class="px-4 py-3 border border-gray-200">Tanggal</th>
                    <th class="px-4 py-3 border border-gray-200">Bayar</th>
                    <th class="px-4 py-3 border border-gray-200">Status</th>
                    <th class="px-4 py-3 border border-gray-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @forelse($reservasis as $i => $item)
                @php $s = strtoupper($item->status ?? 'PENDING'); @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                    <td class="px-4 py-3 border border-gray-200">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 border border-gray-200 font-mono text-xs text-gray-700 dark:text-gray-200">{{ $item->kode_booking }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $item->nomor_antrian ?? '-' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $item->pasien->nama_lengkap ?? '-' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $item->poli->nama_poli ?? '-' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ $item->dokter->nama ?? '-' }}</td>
                    <td class="px-4 py-3 border border-gray-200">{{ \Carbon\Carbon::parse($item->tanggal_reservasi)->format('d-m-Y') }}</td>
                    <td class="px-4 py-3 border border-gray-200">
                        @if(strtolower($item->cara_bayar) === 'bpjs')
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">BPJS</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">UMUM</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        @if($s === 'VERIFIED')
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Terverifikasi</span>
                        @elseif($s === 'REJECTED')
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Ditolak</span>
                        @elseif($s === 'CANCELLED')
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">Dibatalkan</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Menunggu</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 border border-gray-200">
                        <div class="flex items-center gap-2">
                            @if($s === 'PENDING')
                                <a href="{{ route('admin.reservasi.verify.form', $item->id) }}" class="px-3 py-1 bg-green-600 text-white rounded text-xs">Verifikasi</a>
                                <a href="{{ route('admin.reservasi.reject.form', $item->id) }}" class="px-3 py-1 bg-red-600 text-white rounded text-xs">Tolak</a>
                            @elseif($s === 'VERIFIED')
                                <a href="{{ route('admin.reservasi.print', $item->id) }}" target="_blank" class="px-3 py-1 bg-indigo-600 text-white rounded text-xs">Unduh</a>
                            @else
                                <span class="text-xs text-gray-500">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-6 text-center text-gray-500">Data reservasi belum ada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 sm:px-6 border-t dark:border-gray-700 text-right">
        {{-- pagination placeholder --}}
        @if(is_object($reservasis) && method_exists($reservasis, 'links'))
            {{ $reservasis->links() }}
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function(){
        // Inline reject panel handlers (per-row)
        document.querySelectorAll('.inline-reject-form').forEach(function(form){
            var openBtn = form.querySelector('.open-inline-reject');
            var panel = form.querySelector('.inline-reject-panel');
            var textarea = form.querySelector('.inline-reject-reason');
            var cancelBtn = form.querySelector('.inline-reject-cancel');
            var submitBtn = form.querySelector('.inline-reject-submit');
            var hidden = form.querySelector('.alasan-input');

            if (!openBtn || !panel) return;

            openBtn.addEventListener('click', function(e){
                e.preventDefault();
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                if (panel.style.display === 'block' && textarea) textarea.focus();
            });

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(){
                    panel.style.display = 'none';
                });
            }

            if (submitBtn) {
                submitBtn.addEventListener('click', function(){
                    var val = (textarea && textarea.value) ? textarea.value.trim() : '';
                    if (!val) {
                        alert('Alasan penolakan wajib diisi.');
                        if (textarea) textarea.focus();
                        return;
                    }
                    if (hidden) hidden.value = val;
                    form.submit();
                });
            }
        });
    })();
</script>

<!-- Inline reject panels used per-row; modal removed -->
@endpush
