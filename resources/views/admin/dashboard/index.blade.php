@extends('admin.layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <p class="text-gray-600 mt-1">Ringkasan singkat operasi harian.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border-t-4 border-indigo-500">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Poli</p>
                    <div class="text-2xl font-bold mt-1">{{ $totalPoli }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border-t-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>               
                 </div>
                <div>
                    <p class="text-sm text-gray-500">Total Dokter</p>
                    <div class="text-2xl font-bold mt-1">{{ $totalDokter }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5 border-t-4 border-yellow-500">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-md">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Reservasi Hari Ini</p>
                    <div class="text-2xl font-bold mt-1">{{ $reservasiHariIni }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-700">Aktivitas Terbaru</h3>
                    <p class="text-xs text-gray-500 mt-1">Menampilkan 5 reservasi terbaru.</p>
                </div>
                <div class="text-xs text-gray-500">&nbsp;</div>
            </div>

            <div class="mt-4">
                <ul class="divide-y dark:divide-gray-700">
                    @forelse($recentReservasi ?? [] as $r)
                        @php
                            $status = strtoupper($r->status ?? 'PENDING');
                            $dateLabel = '';
                            try { $dateLabel = \Carbon\Carbon::parse($r->tanggal_reservasi)->format('d M Y'); } catch (\Exception $e) { $dateLabel = $r->tanggal_reservasi ?? '-'; }
                            $initials = collect(explode(' ', trim($r->pasien->nama_lengkap ?? '-')))->map(function($p){ return mb_substr($p,0,1); })->take(2)->join('');
                        @endphp

                        <li class="py-3 flex items-center gap-4">
                            <div class="flex-none">
                                <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-semibold">{{ $initials }}</div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="truncate">
                                        <a href="{{ route('admin.reservasi.index') }}" class="text-sm font-medium text-gray-800 dark:text-gray-100 hover:underline">{{ $r->pasien->nama_lengkap ?? '-' }}</a>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ optional($r->poli)->nama_poli ?? '-' }} • {{ $dateLabel }}</div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        @if($status === 'VERIFIED')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-700">Terverifikasi</span>
                                        @elseif($status === 'REJECTED')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-100 text-red-700">Ditolak</span>
                                        @elseif($status === 'CANCELLED')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">Dibatalkan</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                                @if(!empty($r->kode_booking))
                                    <div class="mt-2 text-xs text-gray-400">Kode: <span class="font-mono text-xs text-gray-600">{{ $r->kode_booking }}</span></div>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-gray-500">Belum ada aktivitas</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
