<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard ZeroCourse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <h3 class="text-lg font-bold mb-4">Kursus yang saya ikuti:</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($enrolledCourses as $course)
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h4 class="font-bold text-xl">{{ $course->title }}</h4>
                        {{-- Progress bar dari perhitungan controller tadi --}}
                        <div class="mt-4">
                            <p>Progres: {{ number_format($course->progress, 0) }}%</p>
                            <div class="w-full bg-gray-200 h-2 rounded-full mt-1">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $course->progress }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Kursus Anda</h3>
                @forelse(auth()->user()->enrolledCourses as $course)
                    <div class="border-b py-4 flex justify-between items-center">
                        <div>
                            <h4 class="font-bold">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $course->category->name }}</p>
                        </div>
                        <a href="{{ route('courses.show', $course->slug) }}" class="text-blue-600 hover:underline">Lanjut Belajar</a>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada kursus yang diikuti. <a href="{{ route('courses.index') }}" class="text-blue-600">Cari kursus baru?</a></p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>