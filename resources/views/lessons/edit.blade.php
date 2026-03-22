<x-app-layout>
    {{-- 
        BAGIAN 1: ASSETS (Rich Text Editor) 
        Pastikan Trix Editor terpasang untuk editing konten artikel.
    --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    <style>
        /* Custom Trix Styling agar sesuai dengan tema modern */
        trix-editor {
            border-radius: 1rem !important;
            border-color: #e5e7eb !important; /* gray-200 */
            padding: 1rem !important;
        }
        trix-toolbar .trix-button-group {
            border-color: #e5e7eb !important;
        }
    </style>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <div class="mb-6">
                <a href="{{ route('courses.show', $lesson->module->course->slug) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    Kembali ke Detail Kursus
                </a>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-3xl overflow-hidden">
                {{-- 
                    BAGIAN 2: HEADER EDIT 
                --}}
                <div class="p-8 border-b border-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
                            ✏️
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 leading-tight">Edit Materi</h2>
                            <p class="text-sm text-gray-500 font-medium italic">{{ $lesson->title }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('lessons.update', $lesson->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-8">
                            
                            {{-- 
                                BAGIAN 3: INPUT JUDUL & VIDEO 
                            --}}
                            <div class="space-y-6">
                                {{-- Judul Materi --}}
                                <div class="space-y-2">
                                    <x-input-label for="title" value="Judul Materi" class="font-black text-xs uppercase tracking-widest text-gray-500" />
                                    <x-text-input id="title" name="title" type="text" 
                                                  class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 p-4 transition-all" 
                                                  value="{{ old('title', $lesson->title) }}" required />
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                {{-- Link YouTube --}}
                                <div class="space-y-2">
                                    <x-input-label for="video_url" value="ID Video YouTube (Opsional)" class="font-black text-xs uppercase tracking-widest text-gray-500" />
                                    <x-text-input id="video_url" name="video_url" type="text" 
                                                  class="mt-1 block w-full rounded-2xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 p-4 transition-all" 
                                                  value="{{ old('video_url', $lesson->video_url) }}" 
                                                  placeholder="Hanya masukkan ID Video, misal: dQw4w9WgXcQ" />
                                    <p class="mt-2 text-xs text-gray-400 font-medium">Kosongkan jika materi ini hanya berupa teks/artikel saja.</p>
                                    <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                                </div>
                            </div>

                            {{-- 
                                BAGIAN 4: RICH TEXT EDITOR (TRIX) 
                            --}}
                            <div class="space-y-2">
                                <x-input-label for="content" value="Isi Artikel Materi" class="font-black text-xs uppercase tracking-widest text-gray-500" />
                                
                                <div class="mt-1">
                                    <input id="content" type="hidden" name="content" value="{{ old('content', $lesson->content) }}">
                                    <trix-editor input="content" class="min-h-[400px] bg-white text-gray-700 leading-relaxed"></trix-editor>
                                </div>
                                
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            {{-- 
                                BAGIAN 5: ACTION BUTTONS 
                            --}}
                            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-50">
                                <a href="{{ route('courses.show', $lesson->module->course->slug) }}" 
                                   class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-800 transition">
                                    Batalkan
                                </a>

                                <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
                                    SIMPAN PERUBAHAN
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>