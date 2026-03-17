<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Edit Materi: {{ $lesson->title }}</h2>
                
                <form action="{{ route('lessons.update', $lesson->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <x-input-label for="title" value="Judul Materi" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $lesson->title) }}" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="video_url" value="Link Video YouTube (Opsional)" />
                        <x-text-input id="video_url" name="video_url" type="url" class="mt-1 block w-full" 
                                      value="{{ old('video_url', $lesson->video_url) }}" 
                                      placeholder="Contoh: https://www.youtube.com/watch?v=..." />
                        <p class="mt-1 text-xs text-gray-500 italic">Kosongkan jika materi ini tidak memiliki video.</p>
                        <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="content" value="Isi Materi" />
                        
                        <div class="mt-1">
                            <input id="content" type="hidden" name="content" value="{{ old('content', $lesson->content) }}">
                            <trix-editor input="content" class="min-h-[300px] bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></trix-editor>
                        </div>
                        
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                        
                        <a href="{{ route('courses.show', $lesson->module->course->slug) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>