@extends('layouts.app')

@section('content')

<div class="py-8 transition-colors duration-300">
    <div class="max-w-5xl mx-auto px-4">
        <!-- CARD -->
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6 md:p-8 transition-colors duration-300 relative">
            <a href="#" onclick="window.history.back(); return false;"
               class="absolute right-6 top-6 text-white px-4 py-2 rounded-md font-semibold bg-[#2c3e8f] hover:bg-[#233170] dark:bg-teal-600 dark:hover:bg-teal-700 transition shadow-sm">
                KEMBALI
            </a>
            <!-- JUDUL -->
            <div class="mb-6">
                <h2 class="text-lg md:text-xl font-bold text-[#2c3e8f] dark:text-white">
                    List Surat Kontrol
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    <i>Keterangan : <strong>SK</strong> (Surat Kontrol) dan <strong>RI</strong> (Rujukan Internal)</i>
                </p>
            </div>
            <!-- TABLE -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full text-sm">
                    <thead class="bg-teal-700 text-white text-center">
                        <tr>
                            <th class="px-3 py-3">Jenis Surat</th>
                            <th class="px-3 py-3">Ket</th>
                            <th class="px-3 py-3">No Surat Kontrol</th>
                            <th class="px-3 py-3">Tanggal Kontrol</th>
                            <th class="px-3 py-3">Dokter</th>
                            <th class="px-3 py-3">Diagnosa</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $user = Session::get('user');
                            $reservations = [];
                            if ($user && isset($user['id'])) {
                                $reservations = \App\Models\Reservasi::where('patient_id', $user['id'])
                                    ->where('cara_bayar', 'BPJS')
                                    ->whereIn('status', ['PENDING', 'VERIFIED'])
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            }
                        @endphp
                        @forelse($reservations as $r)
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-center">
                                <td class="px-3 py-2">SK</td>
                                <td class="px-3 py-2">Kontrol</td>
                                <td class="px-3 py-2">{{ $r->no_rujukan }}</td>
                                <td class="px-3 py-2">{{ $r->tanggal_rujukan ? \Carbon\Carbon::parse($r->tanggal_rujukan)->format('d/m/Y') : '-' }}</td>
                                <td class="px-3 py-2">{{ optional($r->dokter)->nama ?? '-' }}</td>
                                <td class="px-3 py-2">-</td>
                                <td class="px-3 py-2">
                                    @if($r->status === 'VERIFIED')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">Terverifikasi</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if($r->status === 'VERIFIED')
                                        <a href="{{ route('bpjs.daftar', ['kode' => $r->kode_booking]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded-lg shadow">
                                            PILIH
                                        </a>
                                    @else
                                        <span class="text-gray-500">Menunggu...</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-center">
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2"></td>
                                <td class="px-3 py-2">&nbsp;</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between items-center mt-6">
                <!-- TUTUP -->
               <a href="{{ route('pendaftaran-pasien-lama.menu') }}"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2 rounded-lg transition shadow">
                    TUTUP
               </a>
                <!-- NOMOR RUJUKAN BARU -->
                <a href="{{ route('bpjs.rujukan') }}"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-6 py-2 rounded-lg shadow transition">
                    Masukkan Nomor Rujukan Baru
                </a>
            </div>
        </div>
    </div>
</div>

@endsection