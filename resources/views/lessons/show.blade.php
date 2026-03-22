<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->module->course->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8">
                
                {{-- ==========================================
                     BAGIAN 1: SIDEBAR (DAFTAR MATERI & LOCK)
                     ========================================== --}}
                <div class="w-full md:w-1/4">
                    <div class="bg-white shadow-sm border border-gray-100 rounded-3xl p-5 sticky top-6">
                        <div class="mb-5 px-2">
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Modul Sekarang</p>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $lesson->module->title }}</h3>
                        </div>

                        <div class="space-y-2">
                            @foreach($lesson->module->lessons as $index => $materi)
                                @php
                                    $isCompleted = auth()->user()->hasCompleted($materi->id);
                                    $isActive = $lesson->id === $materi->id;
                                    
                                    // LOGIC LOCK: Materi terkunci jika materi sebelumnya belum selesai
                                    $prevInList = $lesson->module->lessons->get($index - 1);
                                    $isLocked = $prevInList && !auth()->user()->hasCompleted($prevInList->id);
                                @endphp

                                <div>
                                    @if($isLocked)
                                        <div class="flex items-center p-3 rounded-xl bg-gray-50 opacity-50 cursor-not-allowed border border-transparent">
                                            <span class="mr-3 text-xs">🔒</span>
                                            <span class="text-xs text-gray-500 font-medium">{{ $materi->title }}</span>
                                        </div>
                                    @else
                                        <a href="{{ route('lessons.show', $materi->id) }}" 
                                           class="flex items-center p-3 rounded-xl transition-all border {{ $isActive ? 'bg-blue-50 border-blue-200 shadow-sm' : 'hover:bg-gray-50 border-transparent' }}">
                                            <span class="mr-3 text-sm">{{ $isCompleted ? '✅' : '📖' }}</span>
                                            <span class="text-xs {{ $isActive ? 'text-blue-700 font-bold' : 'text-gray-600 font-medium' }}">
                                                {{ $materi->title }}
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach

                            {{-- SIDEBAR: AKSES QUIZ --}}
                            @if($lesson->module->quiz)
                                @php
                                    $allLessonsDone = $lesson->module->lessons->every(fn($l) => auth()->user()->hasCompleted($l->id));
                                @endphp
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    @if($allLessonsDone)
                                        <a href="{{ route('quizzes.show', $lesson->module->quiz->id) }}" 
                                           class="flex items-center p-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-md shadow-indigo-100">
                                            <span class="mr-3">📝</span>
                                            <span class="text-xs font-bold uppercase tracking-wider">Ambil Quiz Modul</span>
                                        </a>
                                    @else
                                        <div class="flex items-center p-3 rounded-xl bg-gray-50 border border-dashed border-gray-200 opacity-60 cursor-not-allowed">
                                            <span class="mr-3">🔒</span>
                                            <span class="text-[10px] text-gray-500 font-bold uppercase">Quiz Terkunci</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- ==========================================
                     BAGIAN 2: MAIN CONTENT (VIDEO & ARTIKEL)
                     ========================================== --}}
                <div class="w-full md:w-3/4">
                    <div class="bg-white shadow-sm border border-gray-100 rounded-3xl p-6 md:p-10">
                        
                        {{-- Video Player --}}
                        @if($lesson->youtube_id)
                            <div class="mb-10 overflow-hidden rounded-2xl shadow-2xl aspect-video bg-black">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}?rel=0" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif

                        <div class="mb-8">
                            <h1 class="text-3xl font-black text-gray-900 mb-4">{{ $lesson->title }}</h1>
                            <div class="h-1 w-20 bg-blue-600 rounded-full"></div>
                        </div>

                        {{-- Konten Text --}}
                        <div class="prose prose-lg max-w-none mb-12 text-gray-700 leading-relaxed">
                            {!! $lesson->content !!}
                        </div>


                        {{-- ==========================================
                             BAGIAN 3: NAVIGASI BAWAH (PROGRESS ACTION)
                             ========================================== --}}
                        <div class="flex flex-col sm:flex-row justify-between items-center border-t border-gray-100 pt-8 gap-6">
                            
                            {{-- Navigasi Mundur --}}
                            <div class="w-full sm:w-auto text-center">
                                @if($lesson->module->lessons->contains($prevLesson))
                                    <a href="{{ route('lessons.show', $prevLesson->id) }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-blue-600 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        Materi Sebelumnya
                                    </a>
                                @endif
                            </div>

                            {{-- Tombol Utama (Mark Complete) --}}
                            <div class="w-full sm:w-auto">
                                @if(auth()->user()->hasCompleted($lesson->id))
                                    <div class="flex items-center justify-center px-8 py-3 bg-emerald-50 text-emerald-600 font-black rounded-2xl border border-emerald-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Materi Selesai
                                    </div>
                                @else
                                    <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95 uppercase text-sm tracking-widest">
                                            Selesai & Lanjut
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- Navigasi Maju / Quiz --}}
                            <div class="w-full sm:w-auto text-center">
                                @if($nextLesson)
                                    @if(auth()->user()->hasCompleted($lesson->id))
                                        <a href="{{ route('lessons.show', $nextLesson->id) }}" class="inline-flex items-center text-sm font-black text-blue-600 hover:text-blue-800 transition">
                                            Materi Selanjutnya
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic font-medium flex items-center justify-center">
                                            Selesaikan materi ini 🔒
                                        </span>
                                    @endif
                                @elseif($module->quiz)
                                    @if(auth()->user()->hasCompleted($lesson->id))
                                        <a href="{{ route('quizzes.show', $module->quiz->id) }}" class="inline-flex items-center px-6 py-3 bg-indigo-100 text-indigo-700 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-200 transition">
                                            Lanjut Ke Quiz 📝
                                        </a>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>