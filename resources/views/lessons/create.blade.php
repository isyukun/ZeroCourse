<x-app-layout>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h2 class="text-xl font-bold mb-4">Tambah Materi ke: {{ $module->title }}</h2>
                
                <form action="{{ route('lessons.store', $module->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label value="Judul Materi" />
                        <x-text-input name="title" class="w-full" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label value="Isi Materi" />
                        
                        <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                        <trix-editor input="content" class="min-h-[300px] bg-white"></trix-editor>
                        
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <x-primary-button>Simpan Materi</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>