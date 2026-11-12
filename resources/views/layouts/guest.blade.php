<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Manajemen Inventaris - Koperasi Desa Merah Putih') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-[#fafafa] min-h-screen relative flex flex-col justify-center items-center p-4 sm:p-6 selection:bg-red-600 selection:text-white">
        <!-- Ambient Background Glows (Nuansa Merah Putih) -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10" aria-hidden="true">
            <div class="absolute -top-24 -left-20 w-80 h-80 sm:w-96 sm:h-96 bg-red-100/60 rounded-full blur-3xl opacity-70"></div>
            <div class="absolute top-1/4 -right-20 w-72 h-72 sm:w-96 sm:h-96 bg-rose-100/40 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-20 left-1/3 w-80 h-80 sm:w-96 sm:h-96 bg-red-50/50 rounded-full blur-3xl opacity-60"></div>
        </div>

        {{ $slot }}
    </body>
</html>
