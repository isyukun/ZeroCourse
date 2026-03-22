<x-app-layout>
    {{-- 
        BAGIAN 1: HEADER & ACTION BUTTON
        Navigasi atas yang dinamis tergantung peran user.
    --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 leading-tight">
                    {{ __('Jelajahi Kursus') }}
                </h2>
                <p class="text-sm text-gray-500 font-medium">Temukan materi belajar terbaik untuk upgrade skill kamu.</p>
            </div>

            @if(auth()->user()->role === 'instructor')
                <a href="{{ route('courses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl text-sm font-black transition-all shadow-lg shadow-blue-100 flex items-center gap-2 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    BUAT KURSUS BARU
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 
                BAGIAN 2: EMPTY STATE 
                Tampilan jika database kursus masih kosong.
            --}}
            @if($courses->isEmpty())
                <div class="bg-white p-20 text-center shadow-sm rounded-[3rem] border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-3xl flex items-center justify-center mx-auto mb-6 text-4xl">
                        🔍
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Belum Ada Kursus</h3>
                    <p class="text-gray-500 max-w-xs mx-auto">Instruktur kami sedang menyiapkan materi terbaik untuk kamu. Tunggu sebentar lagi!</p>
                </div>
            @else

                {{-- 
                    BAGIAN 3: GRID KURSUS
                    Looping daftar kursus dengan card desain premium.
                --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <div class="group bg-white overflow-hidden shadow-sm rounded-[2.5rem] flex flex-col border border-gray-100 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500 hover:-translate-y-2">
                            
                            {{-- Thumbnail dengan Overlay Gradien --}}
                            <div class="h-52 bg-gradient-to-br from-blue-500 to-indigo-600 relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20 flex items-center justify-center">
                                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path></svg>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-white/50 font-black text-xs uppercase tracking-widest">ZeroCourse Learning</span>
                                </div>
                                {{-- Badge Kategori --}}
                                <div class="absolute top-5 left-5">
                                    <span class="px-4 py-2 bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase rounded-xl border border-white/30 tracking-widest">
                                        {{ $course->category->name ?? 'Umum' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Info Konten --}}
                            <div class="p-8 flex-grow">
                                <h3 class="text-xl font-black text-gray-900 mb-3 group-hover:text-blue-600 transition-colors leading-tight">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-6">
                                    {{ $course->description }}
                                </p>
                                
                                {{-- Meta Info (Contoh: Jumlah Modul) --}}
                                <div class="flex items-center gap-4 pt-6 border-t border-gray-50">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        {{ $course->modules_count ?? '0' }} Modul
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-gray-400">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Akses Selamanya
                                    </div>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div class="px-8 pb-8 mt-auto">
                                <a href="{{ route('courses.show', $course->slug) }}" class="block text-center bg-gray-900 hover:bg-blue-600 text-white py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-gray-100 hover:shadow-blue-100 uppercase tracking-widest">
                                    Mulai Belajar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- 
                    BAGIAN 4: PAGINATION 
                --}}
                <div class="mt-12 px-4">
                    {{ $courses->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>