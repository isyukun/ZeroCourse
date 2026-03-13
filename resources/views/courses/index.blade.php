<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jelajahi Kursus') }}
            </h2>
            {{-- Tombol ini hanya muncul jika user adalah instruktur --}}
            @if(auth()->user()->role === 'instructor')
                <a href="{{ route('courses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                    + Buat Kursus Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($courses->isEmpty())
                <div class="bg-white p-12 text-center shadow-sm sm:rounded-lg">
                    <p class="text-gray-500 text-lg">Belum ada kursus yang tersedia saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col border border-gray-100 hover:shadow-md transition">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">Thumbnail Kursus</span>
                            </div>

                            <div class="p-6 flex-grow">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs font-bold uppercase rounded">
                                        {{ $course->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    {{ $course->description }}
                                </p>
                            </div>

                            <div class="p-6 pt-0 mt-auto">
                                <a href="{{ route('courses.show', $course->slug) }}" class="block text-center bg-gray-800 hover:bg-gray-900 text-white py-2 rounded-md font-semibold transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>