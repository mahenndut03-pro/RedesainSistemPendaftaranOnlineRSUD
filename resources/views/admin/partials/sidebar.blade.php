<div class="h-full flex flex-col bg-transparent">
    <div class="flex items-center justify-between p-5">
        <div class="text-xl font-bold text-indigo-600">ADMIN RSUD</div>
        <button id="sidebar-close" class="md:hidden p-2 rounded bg-gray-100 dark:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-200" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9.293l4.646-4.647a1 1 0 111.414 1.414L11.414 10.707l4.646 4.646a1 1 0 01-1.414 1.414L10 12.121l-4.646 4.646a1 1 0 01-1.414-1.414l4.646-4.646L3.94 5.06A1 1 0 115.354 3.646L10 8.293z" clip-rule="evenodd"/></svg>
        </button>
    </div>

    <nav class="px-3 space-y-1 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded-md gap-3 {{ request()->is('admin') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
            <span class="w-5">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ url('/admin/dokter') }}" class="flex items-center px-3 py-2 rounded-md gap-3 {{ request()->is('admin/dokter*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
            <span class="w-5">👩‍⚕️</span>
            <span>Data Dokter</span>
        </a>

        <a href="{{ url('/admin/jadwal') }}" class="flex items-center px-3 py-2 rounded-md gap-3 {{ request()->is('admin/jadwal*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
            <span class="w-5">📅</span>
            <span>Jadwal Dokter</span>
        </a>

        <a href="{{ url('/admin/poli') }}" class="flex items-center px-3 py-2 rounded-md gap-3 {{ request()->is('admin/poli*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
            <span class="w-5">🏥</span>
            <span>Poli</span>
        </a>

        <a href="{{ route('admin.reservasi.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-md {{ request()->is('admin/reservasi*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-50 dark:hover:bg-gray-700' }}">
            <span class="w-5">📨</span>
            <span>Reservasi</span>
        </a>
    </nav>

    <div class="mt-auto p-4 text-xs text-gray-500">
        Versi Admin • RSUD
    </div>
</div>

