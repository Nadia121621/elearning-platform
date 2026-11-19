<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.courses.index') }}" class="text-sm text-gray-500 hover:underline">&larr; Semua course</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $course->title }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600">{{ $course->description }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <h3 class="px-6 py-4 font-semibold text-gray-800 border-b border-gray-200">
                    Daftar Lesson ({{ $course->lessons->count() }})
                </h3>
                <ul class="divide-y divide-gray-200">
                    @forelse ($course->lessons as $lesson)
                        <li>
                            <a href="{{ route('student.lessons.show', [$course, $lesson]) }}"
                                class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-sm font-medium text-indigo-600">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="text-gray-900">{{ $lesson->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="px-6 py-8 text-center text-gray-500">Belum ada lesson.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
