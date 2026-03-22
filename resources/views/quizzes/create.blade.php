<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Quiz') }}
        </h2>
    </x-slot>

    {{-- 
        BAGIAN 1: ROOT DATA (Alpine.js)
        Mengelola array pertanyaan secara dinamis di sisi klien.
    --}}
    <div class="py-12 bg-gray-50/50 min-h-screen" 
         x-data="{ 
            questions: [{ id: Date.now(), text: '', options: ['', '', '', ''], correct: 0 }],
            addQuestion() {
                this.questions.push({ id: Date.now(), text: '', options: ['', '', '', ''], correct: 0 });
            },
            removeQuestion(index) {
                if(this.questions.length > 1) this.questions.splice(index, 1);
            }
         }">
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('modules.quizzes.store', $module->id) }}" method="POST" class="space-y-6">
                @csrf

                {{-- 
                    BAGIAN 2: HEADER FORM (PENGATURAN UTAMA)
                --}}
                <div class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl">
                    <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-6">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                            📝
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 leading-tight">Buat Quiz Baru</h2>
                            <p class="text-sm text-gray-500 font-medium">Modul: <span class="text-blue-600">{{ $module->title }}</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Judul Quiz</label>
                            <input type="text" name="title" 
                                   class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all" 
                                   placeholder="Misal: Ujian Akhir Modul 1" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Skor Minimum Lulus (%)</label>
                            <input type="number" name="minimum_score" value="70" 
                                   class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 transition-all" required>
                        </div>
                    </div>
                </div>

                {{-- 
                    BAGIAN 3: DYNAMIC QUESTION LIST
                    Looping otomatis berdasarkan data Alpine.js.
                --}}
                <div class="space-y-6">
                    <template x-for="(question, index) in questions" :key="question.id">
                        <div class="bg-white p-8 shadow-sm border border-gray-100 rounded-3xl relative group animate-fade-in">
                            
                            {{-- Badge Nomor & Tombol Hapus --}}
                            <div class="flex justify-between items-center mb-6">
                                <span class="bg-blue-600 text-white px-5 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100" 
                                      x-text="'Pertanyaan #' + (index + 1)"></span>
                                
                                <button type="button" @click="removeQuestion(index)" 
                                        class="text-red-400 hover:text-red-600 transition-colors p-2 hover:bg-red-50 rounded-xl"
                                        x-show="questions.length > 1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            {{-- Input Soal --}}
                            <div class="mb-6">
                                <textarea :name="'questions['+index+'][text]'" 
                                          class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-2xl p-4 min-h-[100px] transition-all" 
                                          placeholder="Tulis soal pertanyaan di sini..." required></textarea>
                            </div>
                            
                            {{-- Input Pilihan Jawaban --}}
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Opsi Jawaban (Pilih Satu yang Benar)</label>
                                
                                <template x-for="(option, optIndex) in question.options" :key="optIndex">
                                    <div class="flex items-center gap-4 group/option">
                                        {{-- Radio Button untuk Jawaban Benar --}}
                                        <input type="radio" 
                                               :name="'questions['+index+'][correct]'" 
                                               :value="optIndex" 
                                               class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300 transition-all" 
                                               required>
                                        
                                        {{-- Text Input Pilihan --}}
                                        <input type="text" 
                                               :name="'questions['+index+'][options]['+optIndex+'][text]'" 
                                               class="w-full border-gray-100 bg-gray-50/50 focus:bg-white focus:border-blue-500 rounded-xl p-3 text-sm transition-all" 
                                               :placeholder="'Pilihan ' + (optIndex + 1)" required>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- 
                    BAGIAN 4: ACTIONS (TAMBAH & SIMPAN)
                --}}
                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="button" @click="addQuestion()" 
                            class="flex-1 bg-white border-2 border-dashed border-gray-200 text-gray-500 py-4 rounded-3xl font-bold hover:border-blue-400 hover:text-blue-600 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Pertanyaan Lainnya
                    </button>
                    
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-4 rounded-3xl font-black text-lg hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all active:scale-95">
                        Simpan Semua & Publish Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>