@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Dokter</p>
        <h2 class="text-2xl font-bold mt-1">—</h2>
    </div>

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Poli</p>
        <h2 class="text-2xl font-bold mt-1">—</h2>
    </div>

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-xl shadow">
        <p class="text-sm text-gray-500">Jadwal Aktif</p>
        <h2 class="text-2xl font-bold mt-1">—</h2>
    </div>

    <div class="bg-white dark:bg-[#1e293b] p-4 rounded-xl shadow">
        <p class="text-sm text-gray-500">Reservasi Hari Ini</p>
        <h2 class="text-2xl font-bold mt-1">—</h2>
    </div>

</div>
@endsection
