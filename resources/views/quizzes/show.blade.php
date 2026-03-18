<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST" class="bg-white p-8 shadow rounded-2xl">
                @csrf
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-gray-900">{{ $quiz->title }}</h1>
                    <p class="text-gray-500">Pilihlah jawaban yang paling tepat.</p>
                </div>

                <div class="space-y-10">
                    @foreach($quiz->questions as $index => $question)
                    <div>
                        <p class="font-bold text-lg mb-4 text-gray-800">{{ $index + 1 }}. {{ $question->question_text }}</p>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($question->options as $option)
                            <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition group">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="text-blue-600 focus:ring-blue-500" required>
                                <span class="ml-3 text-gray-700 group-hover:text-blue-900 font-medium">{{ $option->option_text }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="w-full mt-10 bg-emerald-600 text-white py-4 rounded-2xl font-black text-lg hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">
                    KIRIM JAWABAN
                </button>
            </form>
        </div>
    </div>
</x-app-layout>