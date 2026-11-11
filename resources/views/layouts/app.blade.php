@php
    $pageTitle = $title ?? (config('roles-and-permissions.ui.title') ?: __('roles-and-permissions::ui.title'));
    $brand = config('roles-and-permissions.ui.brand');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    @livewireStyles
</head>
<body class="h-full text-slate-100 antialiased">
    <div class="mx-auto flex min-h-full max-w-7xl flex-col px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                @if ($brand)
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-400">{{ $brand }}</p>
                @endif
                <h1 class="mt-1 text-2xl font-semibold">{{ $pageTitle }}</h1>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="mt-10 border-t border-slate-800 pt-4 text-xs text-slate-500">
            {{ __('roles-and-permissions::ui.footer') }}
        </footer>
    </div>

    @livewireScripts
</body>
</html>
