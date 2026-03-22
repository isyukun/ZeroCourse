<x-app-layout>
    {{-- 
        BAGIAN 1: HEADER & KONTEKS
    --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight">
                    {{ __('Buat Kursus Baru') }}
                </h2>
                <p class="text-sm text-gray-500 font-medium">Siapkan kurikulum terbaik untuk siswa Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigasi Kembali --}}
            <div class="mb-6">
                <a href="{{ route('courses.index') }}" class="text-sm font-bold text-gray-400 hover:text-blue-600 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                    Kembali ke Daftar Kursus
                </a>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-[2.5rem] overflow-hidden">
                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-50">
                    @csrf

                    {{-- 
                        BAGIAN 2: DATA UTAMA (JUDUL & KATEGORI)
                    --}}
                    <div class="p-8 md:p-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Judul Kursus --}}
                            <div class="space-y-2">
                                <x-input-label for="title" :value="__('Judul Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                                <input id="title" name="title" type="text" 
                                       class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-bold text-gray-700" 
                                       placeholder="Misal: Laravel Mastery 2026"
                                       value="{{ old('title') }}" required autofocus />
                                <x-input-error :messages="$errors->get('title')" />
                            </div>

                            {{-- Kategori --}}
                            <div class="space-y-2">
                                <x-input-label for="category_id" :value="__('Kategori')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                                <div class="relative">
                                    <select name="category_id" id="category_id" 
                                            class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-bold text-gray-600 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('category_id')" />
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="space-y-2">
                            <x-input-label for="description" :value="__('Deskripsi Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400" />
                            <textarea id="description" name="description" rows="5" 
                                      class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all text-gray-600 leading-relaxed" 
                                      placeholder="Jelaskan apa yang akan dipelajari oleh siswa..."
                                      required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" />
                        </div>
                    </div>

                    {{-- 
                        BAGIAN 3: UPLOAD THUMBNAIL (VISUAL DROPZONE)
                    --}}
                    <div class="p-8 md:p-10 bg-gray-50/30">
                        <x-input-label :value="__('Thumbnail Kursus')" class="font-black text-xs uppercase tracking-widest text-gray-400 mb-6" />
                        
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            {{-- Preview Area --}}
                            <div id="preview-container" class="relative group">
                                <div id="placeholder-box" class="w-64 h-40 bg-white rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300">
                                    <span class="text-4xl mb-2">🖼️</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Belum Ada Gambar</span>
                                </div>
                                <img id="image-preview" src="#" 
                                     class="hidden w-64 h-40 object-cover rounded-3xl border-4 border-white shadow-xl">
                            </div>

                            {{-- Dropzone Input --}}
                            <div class="flex-grow w-full">
                                <label for="thumbnail" class="relative group block cursor-pointer">
                                    <input type="file" name="thumbnail" id="thumbnail" accept="image/*" onchange="previewImage(event)" class="hidden">
                                    <div class="border-2 border-dashed border-gray-200 group-hover:border-blue-400 group-hover:bg-blue-50 rounded-2xl p-10 text-center transition-all bg-white shadow-sm">
                                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 4m4 0v12"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-600">Klik untuk upload gambar</p>
                                        <p class="text-xs text-gray-400 mt-1">PNG, JPG atau JPEG (Maks. 2MB)</p>
                                    </div>
                                </label>
                                <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- 
                        BAGIAN 4: ACTION FOOTER
                    --}}
                    <div class="p-8 flex items-center justify-end bg-white gap-4">
                        <a href="{{ route('courses.index') }}" class="px-6 py-4 text-sm font-bold text-gray-400 hover:text-gray-900 transition">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-12 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 uppercase tracking-widest">
                            Simpan & Lanjutkan
                        </button>
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
                const container = document.getElementById('preview-container');
                const placeholder = document.getElementById('placeholder-box');
                
                output.src = reader.result;
                output.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>