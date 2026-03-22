<x-app-layout>
    {{-- Header Khusus Quiz (Opsional, bisa disesuaikan) --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Ujian Modul') }}
            </h2>
            <span class="px-4 py-1 bg-amber-100 text-amber-700 text-xs font-black rounded-full border border-amber-200 uppercase tracking-widest">
                Wajib Lulus
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ==========================================
                 BAGIAN 1: INFO QUIZ & PROGRES
                 ========================================== --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-black text-gray-900 mb-2">{{ $quiz->title }}</h1>
                <p class="text-gray-500 font-medium">Pastikan semua pertanyaan terjawab sebelum mengirim.</p>
                
                {{-- Progress Bar Soal --}}
                <div class="mt-6 flex items-center gap-2 justify-center">
                    <div class="h-1.5 w-48 bg-gray-200 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full" style="width: 100%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                        {{ count($quiz->questions) }} Pertanyaan Total
                    </span>
                </div>
            </div>

            <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
                @csrf
                
                {{-- ==========================================
                     BAGIAN 2: DAFTAR PERTANYAAN
                     ========================================== --}}
                <div class="space-y-8">
                    @foreach($quiz->questions as $index => $question)
                    <div class="bg-white p-6 md:p-8 shadow-sm border border-gray-100 rounded-3xl transition-all hover:shadow-md">
                        {{-- Judul Pertanyaan --}}
                        <div class="flex gap-4 mb-6">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center font-black text-sm">
                                {{ $index + 1 }}
                            </span>
                            <p class="font-bold text-lg text-gray-800 leading-relaxed">
                                {{ $question->question_text }}
                            </p>
                        </div>

                        {{-- Pilihan Jawaban --}}
                        <div class="grid grid-cols-1 gap-3 ml-0 md:ml-12">
                            @foreach($question->options as $option)
                            <label class="relative flex items-center p-4 border border-gray-100 rounded-2xl cursor-pointer hover:bg-blue-50/50 hover:border-blue-200 transition-all group overflow-hidden">
                                {{-- Hidden Radio --}}
                                <input type="radio" 
                                       name="answers[{{ $question->id }}]" 
                                       value="{{ $option->id }}" 
                                       class="peer hidden" 
                                       required>
                                
                                {{-- Custom Radio Circle --}}
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                                </div>

                                <span class="ml-4 text-gray-600 font-semibold group-hover:text-gray-900 transition-colors">
                                    {{ $option->option_text }}
                                </span>

                                {{-- Background Highlight on Check --}}
                                <div class="absolute inset-0 bg-blue-50/50 opacity-0 peer-checked:opacity-100 -z-10 transition-opacity"></div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ==========================================
                     BAGIAN 3: TOMBOL SUBMIT (FINAL)
                     ========================================== --}}
                <div class="mt-12 p-8 bg-white border border-gray-100 rounded-3xl shadow-sm text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Periksa kembali sebelum mengirim!</p>
                    <button type="submit" class="w-full bg-emerald-600 text-white py-5 rounded-2xl font-black text-xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <span>SELESAIKAN UJIAN</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>