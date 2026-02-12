<header class="bg-white dark:bg-[#0f172a] border-b dark:border-gray-700 px-4 md:px-6 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <button id="sidebar-open" class="md:hidden p-2 rounded bg-gray-100 dark:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-200" viewBox="0 0 20 20" fill="currentColor"><path d="M3 5h14M3 10h14M3 15h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

        <h1 class="text-lg font-semibold">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-sm text-gray-500 hidden sm:block">Mode Admin Lokal</div>
    </div>
</header>
