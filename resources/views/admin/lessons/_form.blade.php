@csrf

<div>
    <x-input-label for="title" value="Judul lesson" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
        :value="old('title', $lesson->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" value="Deskripsi (opsional)" />
    <textarea id="description" name="description" rows="4"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $lesson->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-gray-600 hover:underline">Batal</a>
</div>
