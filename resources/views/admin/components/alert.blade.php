@props(['type' => 'success'])

<div class="alert alert-{{ $type }} alert-dismissible fade show mt-2" role="alert" style="font-size: 16px;">
    {{ $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

@push('scripts')
    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) alert.classList.remove('show');
        }, 3500);
    </script>
@endpush
