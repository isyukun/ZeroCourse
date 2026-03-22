<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- ==========================================
                 BAGIAN 1: PANEL INSTRUKTUR (KELOLA KURSUS)
                 ========================================== --}}
            @if($user->role === 'instructor' || $user->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-8 border-blue-600">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tight">Kursus Yang Saya Buka</h3>
                            <p class="text-sm text-gray-500">Kelola kurikulum dan pantau jumlah siswa anda.</p>
                        </div>
                        <a href="{{ route('courses.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold text-xs uppercase hover:bg-blue-700 transition shadow-sm">
                            + Buat Kursus Baru
                        </a>
                    </div>

                    @if($myCourses->isEmpty())
                        <p class="text-gray-500 italic text-center py-4 italic border border-dashed rounded-xl">Anda belum memiliki kursus aktif.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($myCourses as $course)
                                <div class="border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition bg-white">
                                    <h4 class="font-black text-gray-900 mb-4 truncate text-lg">{{ $course->title }}</h4>
                                    <div class="flex justify-between text-xs text-gray-500 mb-4 bg-gray-50 p-2 rounded-lg">
                                        <span>👥 {{ $course->enrolled_users_count }} Siswa</span>
                                        <span>📚 {{ $course->modules_count }} Modul</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('courses.show', $course->slug) }}" class="text-center py-2 bg-slate-800 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-slate-900 transition">KELOLA</a>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="text-center py-2 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase hover:bg-gray-200 transition">EDIT</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif


            {{-- ==========================================
                 BAGIAN 2: PANEL SISWA (PROGRES BELAJAR)
                 ========================================== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-8 border-emerald-500">
                <h3 class="text-xl font-bold text-gray-900 uppercase mb-6 tracking-tight">Kelas Yang Saya Ambil</h3>

                @if($enrolledCourses->isEmpty())
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 mb-4">Anda belum bergabung di kelas manapun.</p>
                        <a href="{{ route('courses.index') }}" class="inline-block px-6 py-2 bg-emerald-600 text-white rounded-full font-bold text-sm hover:bg-emerald-700 transition">Cari Kursus Sekarang &rarr;</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($enrolledCourses as $course)
                            {{-- Container Kartu Kursus (Wajib overflow-hidden untuk memotong pita yang keluar batas) --}}
                            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                                
                                {{-- PERBAIKAN PITA LULUS (RIBBON)
                                     - Menggunakan rotasi 45 derajat
                                     - Diposisikan absolute di pojok kanan atas
                                     - Ukuran padding dikecilkan agar proporsional --}}
                                @if($course->progress == 100)
                                    <div class="absolute -right-10 top-5 bg-yellow-400 text-yellow-900 px-12 py-1 rotate-45 font-black text-[10px] shadow-sm z-20">
                                        LULUS
                                    </div>
                                @endif

                                <div class="flex justify-between items-center mb-4 pr-6">
                                    <h4 class="text-lg font-extrabold text-gray-900 truncate">{{ $course->title }}</h4>
                                    <span class="font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg text-sm">{{ $course->progress }}%</span>
                                </div>

                                {{-- Track Progress Bar --}}
                                <div class="w-full bg-gray-100 rounded-full h-3 mb-6 overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 ease-out" 
                                         style="width: {{ $course->progress }}%">
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    @if($course->first_lesson)
                                        <a href="{{ route('lessons.show', $course->first_lesson->id) }}" class="block w-full text-center bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-sm active:transform active:scale-95">
                                            {{ $course->progress > 0 ? 'LANJUTKAN BELAJAR' : 'MULAI BELAJAR' }}
                                        </a>
                                    @endif

                                    {{-- Info Tambahan (Logic Quiz) --}}
                                    @if($course->progress > 80 && $course->progress < 100)
                                        <div class="bg-orange-50 border border-orange-100 p-2 rounded-lg">
                                            <p class="text-[10px] text-center text-orange-600 font-bold animate-pulse">
                                                ⚠️ Selesaikan materi untuk membuka Quiz Akhir!
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>