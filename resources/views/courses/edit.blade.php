<x-app-layout>
    {{-- 
        BAGIAN 1: HEADER & BREADCRUMB
    --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight">
                    {{ __('Edit Kursus') }}
                </h2>
                <p class="text-sm text-gray-500 font-medium">Memperbarui informasi untuk: <span class="text-blue-600">{{ $course->title }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigasi Cepat --}}
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('courses.show', $course->slug) }}" class="text-sm font-bold text-gray-400 hover:text-blue-600 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                    Batal & Kembali
                </a>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-[2.5rem] overflow-hidden">
                <form action="{{ route('courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-50">
                    @csrf
                    @method('PUT')

                    {{-- 
                        BAGIAN 2: INFORMASI UTAMA
                    --}}
                    <div class="p-8 md:p-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Judul --}}
                            <div class="space-y-2">
                                <x-input-label for="title" :value="__('Judul Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                                <input id="title" name="title" type="text" 
                                       class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-bold text-gray-700" 
                                       value="{{ old('title', $course->title) }}" required autofocus />
                                <x-input-error :messages="$errors->get('title')" />
                            </div>

                            {{-- Kategori --}}
                            <div class="space-y-2">
                                <x-input-label for="category_id" :value="__('Kategori Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                                <select name="category_id" id="category_id" 
                                        class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-bold text-gray-600 appearance-none">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                            <textarea id="description" name="description" rows="4" 
                                      class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all text-gray-600 leading-relaxed" 
                                      required>{{ old('description', $course->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" />
                        </div>
                    </div>

                    {{-- 
                        BAGIAN 3: MEDIA (THUMBNAIL)
                    --}}
                    <div class="p-8 md:p-10 bg-gray-50/30">
                        <x-input-label :value="__('Thumbnail Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400 mb-6" />
                        
                        <div class="flex flex-col md:flex-row items-start gap-8">
                            {{-- Current Preview --}}
                            <div class="space-y-3">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter text-center">Thumbnail Saat Ini</p>
                                @if($course->thumbnail)
                                    <img id="current-image" src="{{ asset('storage/' . $course->thumbnail) }}" 
                                         class="w-64 h-40 object-cover rounded-3xl border-4 border-white shadow-xl transition-opacity duration-500">
                                @else
                                    <div class="w-64 h-40 bg-white rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300">
                                        <span class="text-3xl mb-2">🖼️</span>
                                        <span class="text-[10px] font-bold uppercase">No Image</span>
                                    </div>
                                @endif
                            </div>

                            {{-- New Preview (Hidden by default) --}}
                            <div id="new-preview-container" class="hidden space-y-3 animate-fade-in">
                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-tighter text-center">Preview Baru</p>
                                <img id="image-preview" src="#" 
                                     class="w-64 h-40 object-cover rounded-3xl border-4 border-emerald-400 shadow-xl">
                            </div>

                            {{-- Upload Input --}}
                            <div class="flex-grow space-y-4">
                                <div class="relative group">
                                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" onchange="previewImage(event)"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-gray-200 group-hover:border-blue-400 group-hover:bg-blue-50 rounded-2xl p-8 text-center transition-all">
                                        <svg class="w-8 h-8 mx-auto text-gray-400 group-hover:text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 4m4 0v12"></path></svg>
                                        <p class="text-xs font-bold text-gray-500 group-hover:text-blue-600">Klik atau seret gambar ke sini</p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded-full inline-block">
                                    Tip: Gunakan rasio 16:9 untuk hasil terbaik.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 
                        BAGIAN 4: FOOTER ACTIONS
                    --}}
                    <div class="p-8 flex items-center justify-between bg-white">
                        <div class="hidden md:block">
                            <p class="text-xs text-gray-400 font-medium italic">*Thumbnail lama akan otomatis terhapus jika diganti.</p>
                        </div>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <a href="{{ route('courses.show', $course->slug) }}" class="flex-1 md:flex-none text-center px-6 py-4 text-sm font-bold text-gray-500 hover:text-gray-900 transition">Batal</a>
                            <button type="submit" class="flex-1 md:flex-none bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 uppercase tracking-widest">
                                Update Kursus
                            </button>
                        </div>
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
                
                if(currentImg) {
                    currentImg.classList.add('opacity-30', 'grayscale');
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>