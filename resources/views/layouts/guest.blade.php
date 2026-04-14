@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? "{$title} | ".config('app.name', 'DapurMBG') : config('app.name', 'DapurMBG') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10">
                <a href="/" class="mb-8 inline-flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 7.5 12 4l8 3.5M4 7.5V16L12 20m-8-12.5L12 11m8-3.5V16L12 20m0-9v9"/>
                        </svg>
                    </span>
                    <span>
                        <span class="block text-base font-semibold text-slate-900">DapurMBG</span>
                        <span class="block text-sm text-slate-500">Asset Management</span>
                    </span>
                </a>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
