@extends('layouts.app')

@section('content')

<div class="py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-bold mb-4">Halaman Verifikasi Dihapus</h2>
            <p class="mb-4">Halaman verifikasi telah dipindahkan ke Riwayat. Anda akan diarahkan ke halaman Riwayat pendaftaran.</p>
            <a href="{{ route('pendaftaran-pasien-lama.history') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Ke Riwayat</a>
        </div>
    </div>
</div>

<script>
    setTimeout(function(){
        window.location.href = "{{ route('pendaftaran-pasien-lama.history') }}";
    }, 1200);
</script>
                            <th class="py-3 px-3">Poliklinik</th>
                            <th class="py-3 px-3">Dokter</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reservations as $r)
                        <tr>
                            <td class="py-3 px-3">{{ $r->tanggal_reservasi }}</td>
                            <td class="py-3 px-3 text-center text-gray-500">{{ $r->kode_booking }}</td>
                            <td class="py-3 px-3">{{ \Carbon\Carbon::parse($r->tanggal_reservasi)->translatedFormat('l') }}</td>
                            <td class="py-3 px-3">{{ $r->poli->nama_poli ?? '-' }}</td>
                            <td class="py-3 px-3">{{ $r->dokter->nama ?? '-' }}</td>
                            <td class="py-3 px-3">-</td>

                            @php $s = strtoupper($r->status ?? 'PENDING'); @endphp
                            <td class="py-3 px-3">
                                @if($s === 'VERIFIED')
                                    <span class="bg-green-600 text-white px-4 py-2 rounded w-full inline-block text-center">Terverifikasi</span>
                                @elseif($s === 'REJECTED')
                                    <span class="bg-red-600 text-white px-4 py-2 rounded w-full inline-block text-center">Ditolak</span>
                                @else
                                    <span class="bg-yellow-500 text-white px-4 py-2 rounded w-full inline-block text-center">Menunggu Verifikasi</span>
                                @endif
                            </td>

                            <td class="py-3 px-3">
                                @if($s === 'VERIFIED')
                                    <a href="{{ route('pendaftaran-pasien-lama.print', $r->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded w-full inline-block text-center">Unduh</a>
                                @else
                                        <form method="POST" action="{{ url('/pendaftaran-pasien-lama/history/cancel/'.$r->id) }}" class="cancel-form">
                                            @csrf
                                            <input type="hidden" name="alasan_batal" class="alasan-input" />
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded w-full hover:bg-red-600">Batalkan</button>
                                        </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-4 text-center text-gray-500">Belum ada pendaftaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.cancel-form').forEach(function(f){
        f.addEventListener('submit', function(e){
            e.preventDefault();
            var reason = prompt('Masukkan alasan pembatalan (wajib):');
            if (!reason || reason.trim().length === 0) {
                alert('Alasan pembatalan wajib diisi.');
                return;
            }
            var input = f.querySelector('.alasan-input');
            if (input) input.value = reason;
            f.submit();
        });
    });
});
</script>
@endpush
