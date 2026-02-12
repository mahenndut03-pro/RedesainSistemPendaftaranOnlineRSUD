<!DOCTYPE html>
<html lang="id" class="bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Admin')</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    @include('admin.partials.header')

    <div class="flex flex-1">
        {{-- Sidebar (collapsible on mobile) --}}
        <div id="sidebar" class="hidden md:block md:w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700 shadow transition-transform">
            @include('admin.partials.sidebar')
        </div>

        {{-- Mobile sidebar (overlay) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-25 z-30 hidden"></div>

        {{-- Content --}}
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
