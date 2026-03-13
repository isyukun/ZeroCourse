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

                <div class="flex justify-between border-t pt-6">
                    @if($prevLesson)
                        <a href="{{ route('lessons.show', $prevLesson->id) }}" class="text-blue-600 hover:underline">
                            &larr; {{ $prevLesson->title }}
                        </a>
                    @else
                        <span></span>
                    @endif

                    @if($nextLesson)
                        <a href="{{ route('lessons.show', $nextLesson->id) }}" class="text-blue-600 hover:underline">
                            {{ $nextLesson->title }} &rarr;
                        </a>
                    @endif
                </div>

                <div class="mt-8">
                    <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-700">
                            Tandai Materi Selesai
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>