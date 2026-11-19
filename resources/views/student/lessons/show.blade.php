<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('student.courses.show', $course) }}" class="text-sm text-gray-500 hover:underline">&larr; {{ $course->title }}</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $lesson->title }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex aspect-video items-center justify-center rounded-md bg-gray-100 text-sm text-gray-500">
                        @if ($lesson->hasPlayableVideo())
                            Pemutar video akan ditampilkan di sini.
                        @else
                            Video untuk lesson ini belum tersedia.
                        @endif
                    </div>

                    @if ($lesson->description)
                        <p class="mt-4 text-gray-600">{{ $lesson->description }}</p>
                    @endif
                </div>
            </div>

            <aside class="bg-white shadow-sm sm:rounded-lg h-fit">
                <h3 class="px-6 py-4 font-semibold text-gray-800 border-b border-gray-200">Lesson</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach ($course->lessons as $item)
                        <li>
                            <a href="{{ route('student.lessons.show', [$course, $item]) }}"
                                class="flex items-center gap-3 px-6 py-3 text-sm hover:bg-gray-50 {{ $item->is($lesson) ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-700' }}">
                                <span>{{ $loop->iteration }}.</span>
                                <span>{{ $item->title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        </div>
    </div>
</x-app-layout>
