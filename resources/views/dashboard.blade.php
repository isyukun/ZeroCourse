<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- SECTION: PANEL INSTRUKTUR (Hanya untuk Role Instructor/Admin) --}}
            @if($user->role === 'instructor' || $user->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-8 border-blue-600">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 uppercase">Kursus Yang Saya Buka</h3>
                            <p class="text-sm text-gray-500">Kelola kurikulum dan pantau jumlah siswa anda.</p>
                        </div>
                        <a href="{{ route('courses.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold text-xs uppercase hover:bg-blue-700 transition">
                            + Buat Kursus Baru
                        </a>
                    </div>

                    @if($myCourses->isEmpty())
                        <p class="text-gray-500 italic text-center py-4">Anda belum memiliki kursus aktif.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($myCourses as $course)
                                <div class="border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                                    <h4 class="font-black text-gray-900 mb-4 truncate">{{ $course->title }}</h4>
                                    <div class="flex justify-between text-xs text-gray-500 mb-4">
                                        <span>👥 {{ $course->enrolled_users_count }} Siswa</span>
                                        <span>📚 {{ $course->modules_count }} Modul</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('courses.show', $course->slug) }}" class="text-center py-2 bg-slate-800 text-white rounded-lg text-[10px] font-bold">KELOLA</a>
                                        <a href="{{ route('courses.edit', $course->id) }}" class="text-center py-2 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold">EDIT</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- SECTION: PANEL SISWA (Muncul untuk semua user yang enroll) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-8 border-emerald-500">
                <h3 class="text-xl font-bold text-gray-900 uppercase mb-6">Kelas Yang Saya Ambil</h3>

                @if($enrolledCourses->isEmpty())
                    <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed">
                        <p class="text-gray-500 mb-4">Anda belum bergabung di kelas manapun.</p>
                        <a href="{{ route('courses.index') }}" class="text-emerald-600 font-bold">Cari Kursus Sekarang &rarr;</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($enrolledCourses as $course)
                            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-inner">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-extrabold text-gray-900 truncate w-3/4">{{ $course->title }}</h4>
                                    <span class="font-black text-emerald-600">{{ $course->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-6">
                                    <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $course->progress }}%"></div>
                                </div>
                                @if($course->first_lesson)
                                    <a href="{{ route('lessons.show', $course->first_lesson->id) }}" class="block w-full text-center bg-emerald-600 text-white py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                                        {{ $course->progress > 0 ? 'LANJUTKAN BELAJAR' : 'MULAI BELAJAR' }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>