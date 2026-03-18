<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-blue-600 font-bold mb-2">{{ $course->category->name }}</p>
                <h3 class="text-2xl font-bold mb-4">Deskripsi Kursus</h3>
                <p class="text-gray-700 leading-relaxed">{{ $course->description }}</p>

                <div class="mt-8">
                    {{-- LOGIKA TOMBOL ENROLLMENT (AKSI SISWA) --}}
                    @auth
                        <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800">Siap untuk memulai?</h4>
                                <p class="text-sm text-gray-600">Daftarkan diri kamu untuk mengakses semua materi kursus ini.</p>
                            </div>
                            
                            @if(auth()->id() === $course->user_id)
                                <span class="px-4 py-2 bg-gray-200 text-gray-600 rounded-lg text-sm font-bold">Ini kursus kamu</span>
                            @elseif(auth()->user()->enrolledCourses->contains($course->id))
                                <a href="#" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700">Lanjut Belajar</a>
                            @else
                                <form action="{{ route('enrollment.store', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition">Daftar Sekarang</button>
                                </form>
                            @endif
                        </div>
                    @endauth
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Daftar Modul</h3>
                        @if(auth()->id() === $course->user_id)
                            <button onclick="document.getElementById('modal-module').classList.remove('hidden')" class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-bold">
                                + Tambah Modul
                            </button>
                        @endif
                    </div>

                    <div class="space-y-6">
                        @forelse($course->modules as $module)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-b">
                                <h4 class="font-bold text-gray-800 text-lg">{{ $loop->iteration }}. {{ $module->title }}</h4>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-3">
                                        @if (auth()->id() === $course->user_id)
                                            {{-- Dropdown Tambah Konten (Materi / Quiz) --}}
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open" type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 flex items-center gap-2 transition">
                                                    <span>+ Tambah Konten</span>
                                                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>

                                                {{-- Menu Dropdown --}}
                                                <div x-show="open" 
                                                    @click.away="open = false"
                                                    x-transition
                                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-2xl z-[100] overflow-visible">                                        
                                                    {{-- Pilihan Materi --}}
                                                    <a href="{{ route('modules.lessons.create', $module->id) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition">
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                        <div class="flex flex-col">
                                                            <span class="font-bold">Materi Baru</span>
                                                            <span class="text-[10px] text-gray-500">Video atau Artikel</span>
                                                        </div>
                                                    </a>

                                                    {{-- Pilihan Quiz --}}
                                                    <a href="{{ route('modules.quizzes.create', ['module' => $module->id]) }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 transition border-t border-gray-100">
                                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                        <div class="flex flex-col">
                                                            <span class="font-bold">Quiz Baru</span>
                                                            <span class="text-[10px] text-gray-500">Ujian Pemahaman</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-2">
                                @forelse($module->lessons as $lesson)
                                <div class="group flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        <span class="text-gray-700">{{ $lesson->title }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-1">
                                        {{-- Ikon Lihat --}}
                                        <x-action-link color="blue" href="{{ route('lessons.show', $lesson->id) }}" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </x-action-link>

                                        {{-- Cuma muncul kalau dia Instruktur/Pemilik --}}
                                        @if(auth()->id() === $course->user_id)
                                            {{-- Ikon Edit --}}
                                            <x-action-link color="yellow" href="{{ route('lessons.edit', $lesson->id) }}" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </x-action-link>

                                            {{-- Ikon Hapus --}}
                                            <x-delete-button :route="route('lessons.destroy', $lesson->id)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </x-delete-button>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-400 text-sm italic py-3">Belum ada materi.</p>
                                @endforelse
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                            <p class="text-gray-500">Belum ada modul. Mulai dengan tambah modul baru!</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Modal Sederhana untuk Tambah Modul --}}
                <div id="modal-module" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Modul Baru</h3>
                            <form action="{{ route('courses.modules.store', $course->id) }}" method="POST">
                                @csrf
                                <input type="text" name="title" placeholder="Nama Modul (Contoh: Dasar PHP)" class="w-full border-gray-300 rounded-md shadow-sm mb-4" required>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('modal-module').classList.add('hidden')" class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>