<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->module->title }} - {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <h1 class="text-4xl font-extrabold text-gray-900 mb-6">{{ $lesson->title }}</h1>

                <div class="prose max-w-none mb-10">
                    {!! $lesson->content !!}
                </div>

                {{-- Navigasi Materi --}}
                <div class="flex justify-between border-t pt-6 mb-8">
                    @if($prevLesson)
                        <a href="{{ route('lessons.show', $prevLesson->id) }}" class="text-blue-600 font-semibold hover:underline">
                            &larr; {{ $prevLesson->title }}
                        </a>
                    @else
                        <span></span>
                    @endif

                    @if($nextLesson)
                        {{-- Logika: Hanya bisa klik Next jika materi ini sudah selesai --}}
                        @if(auth()->user()->hasCompleted($lesson->id))
                            <a href="{{ route('lessons.show', $nextLesson->id) }}" class="text-blue-600 font-semibold hover:underline">
                                {{ $nextLesson->title }} &rarr;
                            </a>
                        @else
                            <span class="text-gray-400 cursor-not-allowed">
                                {{ $nextLesson->title }} &rarr; (Selesaikan materi ini)
                            </span>
                        @endif
                    @endif
                </div>

                {{-- Tombol Status Selesai --}}
                <div class="mt-8">
                    @if(auth()->user()->hasCompleted($lesson->id))
                        <div class="flex items-center text-green-600 font-bold bg-green-50 p-4 rounded-lg">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Materi ini sudah diselesaikan!
                        </div>
                    @else
                        <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700 transition">
                                Tandai Materi Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>