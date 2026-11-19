<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Semua course</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $course->title }}</h2>
            </div>
            <a href="{{ route('admin.courses.lessons.create', $course) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Lesson
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-2">
                    @if ($course->is_published)
                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Published</span>
                    @else
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Draft</span>
                    @endif
                    <a href="{{ route('admin.courses.edit', $course) }}" class="ms-3 text-sm text-indigo-600 hover:underline">Edit course</a>
                </div>
                <p class="text-gray-600">{{ $course->description }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3 w-16">No</th>
                            <th class="px-6 py-3">Lesson</th>
                            <th class="px-6 py-3">Video</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($course->lessons as $lesson)
                            <tr>
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                                    @if ($lesson->description)
                                        <div class="text-gray-500">{{ \Illuminate\Support\Str::limit($lesson->description, 80) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">{{ $lesson->video_status }}</span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.courses.lessons.move', [$course, $lesson]) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="direction" value="up">
                                        <button class="px-2 text-gray-600 hover:text-gray-900 disabled:opacity-30" @disabled($loop->first) title="Naik">&uarr;</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.courses.lessons.move', [$course, $lesson]) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="direction" value="down">
                                        <button class="px-2 text-gray-600 hover:text-gray-900 disabled:opacity-30" @disabled($loop->last) title="Turun">&darr;</button>
                                    </form>
                                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="ms-3 text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" class="inline"
                                        onsubmit="return confirm('Hapus lesson ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="ms-3 text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada lesson.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
