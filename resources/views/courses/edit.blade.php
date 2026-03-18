<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kursus: ') }} {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Judul Kursus')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $course->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="category_id" :value="__('Kategori')" />
                        <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Deskripsi')" />
                        <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $course->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="thumbnail" :value="__('Thumbnail Kursus')" />
                        
                        <div class="mt-2 mb-4">
                            <p class="text-xs text-gray-500 mb-2">Thumbnail saat ini:</p>
                            @if($course->thumbnail)
                                <img id="current-image" src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current Thumbnail" class="w-48 h-32 object-cover rounded-lg border shadow-sm">
                            @else
                                <div class="w-48 h-32 bg-gray-100 rounded-lg border flex items-center justify-center text-gray-400 italic text-xs">
                                    Belum ada thumbnail
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 flex items-center gap-4">
                            <input type="file" 
                                   name="thumbnail" 
                                   id="thumbnail" 
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        </div>
                        <p class="mt-1 text-xs text-gray-500 italic text-indigo-600 font-medium">*Kosongkan jika tidak ingin mengganti gambar.</p>
                        <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                        
                        <div id="new-preview-container" class="mt-4 hidden">
                            <p class="text-xs text-emerald-600 mb-2 font-bold uppercase tracking-wider">Preview Gambar Baru:</p>
                            <img id="image-preview" src="#" alt="New Preview" class="w-48 h-32 object-cover rounded-lg border-2 border-emerald-400 shadow-md">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 border-t pt-4">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button>
                            {{ __('Update Kursus') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image-preview');
                const container = document.getElementById('new-preview-container');
                const currentImg = document.getElementById('current-image');
                
                output.src = reader.result;
                container.classList.remove('hidden');
                
                // Beri efek redup pada gambar lama agar fokus ke yang baru
                if(currentImg) currentImg.classList.add('opacity-50');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>