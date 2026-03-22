<x-app-layout>
    {{-- 
        BAGIAN 1: ASSETS & CUSTOM STYLE
        Memastikan editor Trix tampil modern dan rounded.
    --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    <style>
        trix-editor {
            border-radius: 1.25rem !important;
            border-color: #f3f4f6 !important; /* gray-100 */
            background-color: #f9fafb !important; /* gray-50 */
            padding: 1.25rem !important;
            min-height: 350px !important;
        }
        trix-toolbar .trix-button-group {
            border-color: #f3f4f6 !important;
            background-color: white !important;
            border-radius: 0.75rem !important;
        }
    </style>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navigasi Kembali --}}
            <div class="mb-6">
                <a href="{{ route('courses.show', $module->course->slug) }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-blue-600 transition group">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Modul
                </a>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-[2rem] overflow-hidden">
                
                {{-- 
                    BAGIAN 2: HEADER FORM
                --}}
                <div class="p-8 md:p-10 border-b border-gray-50 bg-gradient-to-r from-white to-gray-50/50">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg shadow-blue-100">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 leading-tight">Tambah Materi Baru</h2>
                            <p class="text-sm text-gray-500 font-medium">Menambahkan konten ke modul: <span class="text-blue-600 font-bold">{{ $module->title }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <form action="{{ route('modules.lessons.store', $module->id) }}" method="POST" class="space-y-8">
                        @csrf
                        
                        {{-- 
                            BAGIAN 3: INFORMASI DASAR (JUDUL & VIDEO)
                        --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <x-input-label value="Judul Materi" class="font-black text-[10px] uppercase tracking-[0.15em] text-gray-400" />
                                <input type="text" name="title" 
                                       class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-medium" 
                                       placeholder="Misal: Dasar-dasar Pemrograman" required>
                                <x-input-error :messages="$errors->get('title')" />
                            </div>

                            <div class="space-y-2">
                                <x-input-label value="ID Video YouTube (Opsional)" class="font-black text-[10px] uppercase tracking-[0.15em] text-gray-400" />
                                <input type="text" name="youtube_id" 
                                       class="w-full border-gray-100 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all font-medium" 
                                       placeholder="ID saja: dQw4w9WgXcQ">
                                <p class="text-[10px] text-gray-400 italic">Kosongkan jika hanya berupa teks.</p>
                            </div>
                        </div>

                        {{-- 
                            BAGIAN 4: CONTENT EDITOR (RICH TEXT)
                        --}}
                        <div class="space-y-2">
                            <x-input-label value="Isi Artikel / Materi" class="font-black text-[10px] uppercase tracking-[0.15em] text-gray-400" />
                            
                            <div class="mt-1">
                                <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                                <trix-editor input="content" placeholder="Mulai tulis materi kamu di sini..." class="text-gray-700 leading-relaxed"></trix-editor>
                            </div>
                            
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        {{-- 
                            BAGIAN 5: SUBMIT ACTIONS
                        --}}
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <p class="text-xs text-gray-400 font-medium">Pastikan konten sudah diperiksa sebelum disimpan.</p>
                            
                            <div class="flex gap-4">
                                <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 uppercase text-sm tracking-widest">
                                    Simpan Materi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>