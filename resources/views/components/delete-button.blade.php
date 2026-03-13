@props(['route', 'id' => uniqid()])

<form action="{{ $route }}" method="POST" id="form-{{ $id }}" class="inline-block">
    @csrf
    @method('DELETE')
    <button type="button" class="delete-btn p-2 rounded-lg transition-colors duration-200 text-gray-400 hover:bg-red-50 hover:text-red-600" data-form-id="form-{{ $id }}">
        {{ $slot }}
    </button>
</form>

<script>
    // Skrip ini hanya akan berjalan jika ada tombol hapus di halaman
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak akan bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>