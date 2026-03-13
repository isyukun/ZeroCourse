<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4">Edit Materi: {{ $lesson->title }}</h2>
                
                <form action="{{ route('lessons.update', $lesson->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <x-input-label value="Judul Materi" />
                        <x-text-input name="title" class="w-full" value="{{ old('title', $lesson->title) }}" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label value="Isi Materi" />
                        
                        <input id="content" type="hidden" name="content" value="{{ old('content', $lesson->content) }}">
                        
                        <trix-editor input="content" class="min-h-[300px] bg-white"></trix-editor>
                        
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="flex gap-2">
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                        <a href="{{ route('courses.show', $lesson->module->course->slug) }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>