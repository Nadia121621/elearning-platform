@csrf

<div>
    <x-input-label for="title" value="Judul" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
        :value="old('title', $course->title ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" value="Deskripsi" />
    <textarea id="description" name="description" rows="5" required
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $course->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4">
    <input type="hidden" name="is_published" value="0">
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_published" value="1"
            @checked(old('is_published', $course->is_published ?? false))
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <span class="ms-2 text-sm text-gray-600">Publikasikan course (terlihat oleh student)</span>
    </label>
    <x-input-error :messages="$errors->get('is_published')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
</div>
