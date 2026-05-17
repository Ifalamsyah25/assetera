@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                type: 'success',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Oops...',
                text: "{{ session('error') }}",
                type: 'error',
                timer: 4000,
                showConfirmButton: true
            });
        @endif
        
        @if ($errors->any())
            Swal.fire({
                title: 'Validasi Gagal!',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                type: 'error',
                showConfirmButton: true
            });
        @endif
    });
</script>
@endpush
