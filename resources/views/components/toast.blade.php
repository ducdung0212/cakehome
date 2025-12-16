<script>
$(document).ready(function () {
    // Cấu hình Toastr
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    // Hiển thị thông báo
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif
});
</script>