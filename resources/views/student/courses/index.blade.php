<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Course</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($courses as $course)
                    <a href="{{ route('student.courses.show', $course) }}"
                        class="block bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-lg text-gray-900">{{ $course->title }}</h3>
                        <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                        <p class="mt-4 text-xs font-medium text-indigo-600">{{ $course->lessons_count }} lesson</p>
                    </a>
                @empty
                    <div class="col-span-full bg-white shadow-sm sm:rounded-lg p-8 text-center text-gray-500">
                        Belum ada course yang tersedia.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ $courses->links() }}</div>
        </div>
    </div>
</x-app-layout>
