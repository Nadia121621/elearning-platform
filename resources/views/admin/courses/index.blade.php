<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Course</h2>
            <a href="{{ route('admin.courses.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Course
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Judul</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Lesson</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($courses as $course)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $course->title }}</div>
                                    <div class="text-gray-500">{{ \Illuminate\Support\Str::limit($course->description, 80) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($course->is_published)
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Published</span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $course->lessons_count }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline"
                                        onsubmit="return confirm('Hapus course ini beserta semua lesson-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="ms-3 text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada course.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $courses->links() }}</div>
        </div>
    </div>
</x-app-layout>
