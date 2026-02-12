@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-lg mx-auto px-4">
        <div class="bg-white dark:bg-[#1e2839] rounded-xl border border-gray-200 dark:border-gray-700 shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Masukkan Nomor Rujukan Baru</h2>

            <form method="POST" action="{{ route('bpjs.rujukan.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Rujukan <span class="text-red-500">*</span></label>
                    <input name="no_rujukan" type="text" value="{{ old('no_rujukan') }}" class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-800 dark:text-gray-100">
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Rujukan <span class="text-red-500">*</span></label>
                    <input name="tanggal_rujukan" type="date" value="{{ old('tanggal_rujukan') }}" class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#243447] text-gray-800 dark:text-gray-100">
                </div>
                <div class="mb-4">
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Waktu Pelayanan</label>
                    <div class="flex gap-4">
                        <label><input type="radio" name="waktu" value="PAGI" checked> Pagi</label>
                        <label><input type="radio" name="waktu" value="SIANG"> Siang</label>
                        <label><input type="radio" name="waktu" value="SORE"> Sore</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('bpjs.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Tutup</a>
                    <button class="px-4 py-2 bg-sky-500 text-white rounded">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
