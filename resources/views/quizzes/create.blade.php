<x-app-layout>
    <div class="py-12" x-data="{ qCount: 1 }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('modules.quizzes.store', $module->id) }}" method="POST" class="bg-white p-8 shadow rounded-xl">
                @csrf
                <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Buat Quiz: {{ $module->title }}</h2>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block font-bold mb-1">Judul Quiz</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-lg" placeholder="Misal: Ujian Akhir Modul 1" required>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Skor Minimum Lulus</label>
                        <input type="number" name="minimum_score" value="70" class="w-full border-gray-300 rounded-lg" required>
                    </div>
                </div>

                <div id="questions-container" class="space-y-8">
                    <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 relative">
                        <span class="absolute -top-3 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs">Pertanyaan #1</span>
                        <textarea name="questions[0][text]" class="w-full border-gray-300 rounded-lg mb-4" placeholder="Tulis soal di sini..." required></textarea>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-600">Pilihan Jawaban (Centang yang benar):</label>
                            @for($i=0; $i<4; $i++)
                            <div class="flex items-center gap-3">
                                <input type="radio" name="questions[0][correct]" value="0-{{$i}}" required>
                                <input type="text" name="questions[0][options][{{$i}}][text]" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Pilihan {{$i+1}}" required>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <button type="button" class="text-blue-600 font-bold text-sm">+ Tambah Pertanyaan (Manual/JS)</button>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition">Simpan Quiz</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>