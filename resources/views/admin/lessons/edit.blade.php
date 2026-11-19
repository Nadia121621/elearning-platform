<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Lesson: {{ $course->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}">
                    @method('PUT')
                    @include('admin.lessons._form')
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Video</h3>
                    @php
                        $badge = [
                            'none' => 'bg-gray-100 text-gray-600',
                            'uploading' => 'bg-yellow-100 text-yellow-700',
                            'ready' => 'bg-green-100 text-green-700',
                            'failed' => 'bg-red-100 text-red-700',
                        ][$lesson->video_status] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="rounded-full px-2 py-1 text-xs {{ $badge }}">{{ $lesson->video_status }}</span>
                </div>

                @if ($lesson->hasPlayableVideo())
                    <video controls preload="metadata" class="mt-4 w-full rounded-md bg-black"
                        src="{{ route('lessons.video', [$course, $lesson]) }}"></video>

                    <form method="POST" action="{{ route('admin.courses.lessons.video.destroy', [$course, $lesson]) }}"
                        class="mt-3" onsubmit="return confirm('Hapus video ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-red-600 hover:underline">Hapus video</button>
                    </form>
                @elseif ($lesson->video_status === 'failed')
                    <p class="mt-4 text-sm text-red-600">Upload sebelumnya gagal. Silakan unggah ulang.</p>
                @else
                    <p class="mt-4 text-sm text-gray-500">Belum ada video untuk lesson ini.</p>
                @endif

                <form id="video-form" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.courses.lessons.video.store', [$course, $lesson]) }}" class="mt-6">
                    @csrf
                    <label for="video" class="block text-sm font-medium text-gray-700">
                        {{ $lesson->hasPlayableVideo() ? 'Ganti video' : 'Unggah video' }} (MP4 atau WebM, maks. 200 MB)
                    </label>
                    <input id="video" name="video" type="file" accept="video/mp4,video/webm"
                        class="mt-2 block w-full text-sm text-gray-700 file:me-4 file:rounded-md file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium hover:file:bg-gray-200">

                    <div id="progress-wrap" class="mt-4 hidden">
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                            <div id="progress-bar" class="h-2 bg-indigo-600" style="width: 0%"></div>
                        </div>
                        <p id="progress-label" class="mt-1 text-xs text-gray-600">0%</p>
                    </div>

                    <p id="video-message" class="mt-3 hidden text-sm"></p>

                    <button id="upload-btn" type="submit"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50">
                        Unggah
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('video-form');
            const input = document.getElementById('video');
            const btn = document.getElementById('upload-btn');
            const wrap = document.getElementById('progress-wrap');
            const bar = document.getElementById('progress-bar');
            const label = document.getElementById('progress-label');
            const message = document.getElementById('video-message');

            function show(text, ok) {
                message.textContent = text;
                message.className = 'mt-3 text-sm ' + (ok ? 'text-green-700' : 'text-red-600');
            }

            function reset() {
                btn.disabled = false;
                wrap.classList.add('hidden');
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!input.files.length) {
                    show('Pilih file video terlebih dahulu.', false);
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.upload.onprogress = function (ev) {
                    if (!ev.lengthComputable) return;
                    const pct = Math.round((ev.loaded / ev.total) * 100);
                    bar.style.width = pct + '%';
                    label.textContent = pct < 100 ? pct + '%' : 'Menyimpan video...';
                };

                xhr.onload = function () {
                    let data = {};
                    try { data = JSON.parse(xhr.responseText); } catch (err) {}

                    if (xhr.status >= 200 && xhr.status < 300) {
                        show(data.message || 'Video berhasil diunggah.', true);
                        window.location.reload();
                        return;
                    }

                    reset();
                    const fieldError = data.errors && data.errors.video && data.errors.video[0];
                    show(fieldError || data.message || 'Upload gagal (kode ' + xhr.status + ').', false);
                };

                xhr.onerror = function () {
                    reset();
                    show('Koneksi terputus saat mengunggah. Silakan coba lagi.', false);
                };

                btn.disabled = true;
                message.className = 'hidden';
                wrap.classList.remove('hidden');
                bar.style.width = '0%';
                label.textContent = '0%';

                xhr.send(new FormData(form));
            });
        })();
    </script>
</x-app-layout>
