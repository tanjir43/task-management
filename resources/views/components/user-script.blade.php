<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            const status = xhr.responseJSON.status || 'success';
            if (status === 'success') {
                toastr.success(xhr.responseJSON.message);
            } else if (status === 'error') {
                toastr.error(xhr.responseJSON.message);
            } else if (status === 'warning') {
                toastr.warning(xhr.responseJSON.message);
            } else if (status === 'info') {
                toastr.info(xhr.responseJSON.message);
            }
        }
    });

    $(document).ajaxError(function(event, xhr, settings) {
        if (xhr.status !== 422) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error('An error occurred while processing your request.');
            }
        }
    });

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
