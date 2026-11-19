<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'E-Learning') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-800 antialiased">
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-6xl mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                    <span class="font-semibold text-gray-800">{{ config('app.name') }}</span>
                </a>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3 text-sm">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="rounded-md bg-gray-800 px-4 py-2 font-medium text-white hover:bg-gray-700">
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-2 font-medium text-gray-600 hover:text-gray-900">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="rounded-md bg-gray-800 px-4 py-2 font-medium text-white hover:bg-gray-700">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main>
            <section class="bg-indigo-600">
                <div class="max-w-6xl mx-auto px-4 py-16 text-center sm:px-6 sm:py-24 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">
                        Belajar kapan saja lewat video
                    </h1>
                    <p class="mx-auto mt-4 max-w-2xl text-base text-indigo-100 sm:text-lg">
                        Pilih course, ikuti lesson secara berurutan, dan putar materi video langsung dari browser.
                    </p>
                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="rounded-md bg-white px-6 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                                Lanjut belajar
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="rounded-md bg-white px-6 py-3 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                                Masuk untuk mulai
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="rounded-md border border-indigo-300 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Buat akun gratis
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-3">
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900">Course terstruktur</h3>
                        <p class="mt-2 text-sm text-gray-600">Setiap course berisi lesson yang tersusun berurutan, sehingga alur belajar jelas.</p>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900">Materi video</h3>
                        <p class="mt-2 text-sm text-gray-600">Putar video lesson langsung di halaman, lengkap dengan fitur geser waktu.</p>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow-sm">
                        <h3 class="font-semibold text-gray-900">Akses sesuai peran</h3>
                        <p class="mt-2 text-sm text-gray-600">Admin mengelola konten, student belajar. Video hanya bisa dibuka setelah login.</p>
                    </div>
                </div>
            </section>

            <section class="max-w-6xl mx-auto px-4 pb-16 sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold text-gray-900">Course tersedia</h2>

                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($courses as $course)
                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                            class="block rounded-lg bg-white p-6 shadow-sm transition hover:shadow-md">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $course->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($course->description, 110) }}</p>
                            <p class="mt-4 text-xs font-medium text-indigo-600">{{ $course->lessons_count }} lesson</p>
                        </a>
                    @empty
                        <div class="col-span-full rounded-lg bg-white p-8 text-center text-gray-500 shadow-sm">
                            Belum ada course yang dipublikasikan.
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <footer class="border-t border-gray-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 py-6 text-center text-sm text-gray-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </div>
        </footer>
    </body>
</html>
