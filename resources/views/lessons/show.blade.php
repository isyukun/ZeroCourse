<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->module->course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-6">
                
                {{-- SIDEBAR --}}
                <div class="w-full md:w-1/4">
                    <div class="bg-white shadow sm:rounded-lg p-4 sticky top-6">
                        <h3 class="font-bold text-gray-700 mb-4 px-2 text-lg border-b pb-2">
                            {{ $module->title }}
                        </h3>
                        <div class="space-y-1">
                            @foreach($module->lessons as $index => $materi)
                                @php
                                    $isCompleted = auth()->user()->hasCompleted($materi->id);
                                    $isActive = $lesson->id === $materi->id;
                                    
                                    $prevInList = $module->lessons->get($index - 1);
                                    $isLocked = $prevInList && !auth()->user()->hasCompleted($prevInList->id);
                                @endphp

                                <div>
                                    @if($isLocked)
                                        <div class="flex items-center p-3 rounded-lg bg-gray-50 opacity-60 cursor-not-allowed">
                                            <span class="mr-3">🔒</span>
                                            <span class="text-sm text-gray-500">{{ $materi->title }}</span>
                                        </div>
                                    @else
                                        <a href="{{ route('lessons.show', $materi->id) }}" 
                                           class="flex items-center p-3 rounded-lg transition {{ $isActive ? 'bg-blue-50 border-l-4 border-blue-600' : 'hover:bg-gray-100' }}">
                                            <span class="mr-3">{{ $isCompleted ? '✅' : '📖' }}</span>
                                            <span class="text-sm {{ $isActive ? 'text-blue-700 font-bold' : 'text-gray-700' }}">
                                                {{ $materi->title }}
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach

                            {{-- LINK QUIZ DI SIDEBAR --}}
                            @if($module->quiz)
                                @php
                                    // Quiz terbuka jika SEMUA materi di modul ini sudah selesai
                                    $allLessonsDone = $module->lessons->every(fn($l) => auth()->user()->hasCompleted($l->id));
                                @endphp
                                <div class="mt-4 pt-4 border-t">
                                    @if($allLessonsDone)
                                        <a href="{{ route('quizzes.show', $module->quiz->id) }}" 
                                           class="flex items-center p-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition border border-indigo-200">
                                            <span class="mr-3">📝</span>
                                            <span class="text-sm text-indigo-700 font-bold">Quiz Modul</span>
                                        </a>
                                    @else
                                        <div class="flex items-center p-3 rounded-lg bg-gray-50 opacity-60 cursor-not-allowed border border-dashed">
                                            <span class="mr-3">🔒</span>
                                            <span class="text-sm text-gray-500 italic">Selesaikan materi untuk Quiz</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="w-full md:w-3/4">
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg p-8">
                        @if($lesson->youtube_id)
                            <div class="mb-8 overflow-hidden rounded-xl shadow-lg aspect-video">
                                <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @endif

                        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">{{ $lesson->title }}</h1>

                        <div class="prose max-w-none mb-10 text-gray-800">
                            {!! $lesson->content !!}
                        </div>

                        {{-- NAVIGASI & TOMBOL SELESAI --}}
                        <div class="flex flex-col sm:flex-row justify-between items-center border-t pt-6 gap-4">
                            <div>
                                @if($prevLesson)
                                    <a href="{{ route('lessons.show', $prevLesson->id) }}" class="text-blue-600 font-medium hover:underline">
                                        &larr; Sebelumnya
                                    </a>
                                @endif
                            </div>

                            <div>
                                @if(auth()->user()->hasCompleted($lesson->id))
                                    <div class="flex items-center text-green-600 font-bold bg-green-50 px-4 py-2 rounded-full border border-green-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai
                                    </div>
                                @else
                                    <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                        @csrf
                                        <x-primary-button class="bg-green-600 hover:bg-green-700">Tandai Selesai</x-primary-button>
                                    </form>
                                @endif
                            </div>

                            <div>
                                @if($nextLesson)
                                    @if(auth()->user()->hasCompleted($lesson->id))
                                        <a href="{{ route('lessons.show', $nextLesson->id) }}" class="text-blue-600 font-medium hover:underline">
                                            Selanjutnya &rarr;
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Selesaikan untuk lanjut &rarr;</span>
                                    @endif
                                {{-- JIKA TIDAK ADA MATERI LAGI, ARAHKAN KE QUIZ --}}
                                @elseif($module->quiz)
                                    @if(auth()->user()->hasCompleted($lesson->id))
                                        <a href="{{ route('quizzes.show', $module->quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                            Mulai Quiz &rarr;
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Selesaikan untuk Quiz &rarr;</span>
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