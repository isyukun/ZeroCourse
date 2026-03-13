@props(['title', 'route', 'fieldName' => 'title', 'inputValue' => ''])

<button type="button" onclick="showPrompt{{ $attributes->get('id') }}()" class="text-blue-600 hover:text-blue-800 text-xs font-bold">
    {{ $slot }}
</button>

<script>
    function showPrompt{{ $attributes->get('id') }}() {
        Swal.fire({
            title: '{{ $title }}',
            input: 'text',
            inputValue: '{{ $inputValue }}',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) return 'Input tidak boleh kosong!'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Buat form dinamis untuk kirim data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ $route }}';
                
                // Tambahkan CSRF
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                // Tambahkan Method PUT (karena biasanya untuk update)
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.appendChild(method);

                // Tambahkan input field
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '{{ $fieldName }}';
                input.value = result.value;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>