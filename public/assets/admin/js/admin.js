// ==================== ADMIN GLOBAL JAVASCRIPT ====================

$(document).ready(function () {

    // ==================== SIDEBAR TOGGLE ====================

    // Mobile sidebar toggle
    $('#sidebarToggleBtn, #sidebarToggle').click(function () {
        $('.sidebar').toggleClass('active');
        $('.sidebar-overlay').toggleClass('active');
    });

    // Close sidebar when clicking overlay
    $('.sidebar-overlay').click(function () {
        $('.sidebar').removeClass('active');
        $(this).removeClass('active');
    });

    // Desktop sidebar collapse
    $('#sidebarCollapseBtn').click(function () {
        $('body').toggleClass('sidebar-collapsed');
    });

    // ==================== DROPDOWN AUTO-CLOSE ====================

    // Close dropdowns when clicking outside
    $(document).click(function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });

    // ==================== DATATABLES DEFAULT CONFIG ====================

    // Set default DataTables configuration
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            autoWidth: false,
            responsive: true
        });
    }

    // ==================== SELECT2 DEFAULT CONFIG ====================

    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Chọn...',
            allowClear: true
        });
    }

    // ==================== TOOLTIPS ====================

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ==================== POPOVERS ====================

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // ==================== ALERTS AUTO-DISMISS ====================

    setTimeout(function () {
        $('.alert').fadeOut('slow', function () {
            $(this).remove();
        });
    }, 5000);

    // ==================== CONFIRM DELETE ====================

    $('.btn-delete, .delete-btn').click(function (e) {
        if (!confirm('Bạn có chắc chắn muốn xóa?')) {
            e.preventDefault();
        }
    });

    // ==================== IMAGE PREVIEW ====================

    $('input[type="file"]').change(function (e) {
        var file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = $(this).closest('.mb-3').find('.image-preview');
                if (preview.length === 0) {
                    preview = $('<div class="image-preview mt-2"><img src="" class="img-fluid rounded" style="max-height: 200px;"></div>');
                    $(this).closest('.mb-3').append(preview);
                }
                preview.find('img').attr('src', e.target.result);
            }.bind(this);
            reader.readAsDataURL(file);
        }
    });

    // ==================== FORM VALIDATION ====================

    // Bootstrap form validation
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // ==================== SEARCH FUNCTIONALITY ====================

    $('#globalSearch').on('keyup', function () {
        var searchText = $(this).val().toLowerCase();
        if (searchText.length > 2) {
            // Implement search logic here
            console.log('Searching for:', searchText);
        }
    });

    // ==================== AJAX SETUP ====================

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ==================== NOTIFICATION SYSTEM ====================

    function showNotification(message, type = 'info') {
        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3" 
                 role="alert" style="z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        $('body').append(alert);

        setTimeout(function () {
            alert.fadeOut('slow', function () {
                $(this).remove();
            });
        }, 5000);
    }

    // Make showNotification available globally
    window.showNotification = showNotification;

    // ==================== LOADING OVERLAY ====================

    function showLoading() {
        if ($('.loading-overlay').length === 0) {
            $('body').append(`
                <div class="loading-overlay">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
        }
    }

    function hideLoading() {
        $('.loading-overlay').remove();
    }

    window.showLoading = showLoading;
    window.hideLoading = hideLoading;

    // ==================== NUMBER FORMATTING ====================

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    window.formatCurrency = formatCurrency;

    // ==================== COPY TO CLIPBOARD ====================

    $('.copy-to-clipboard').click(function () {
        var text = $(this).data('text');
        navigator.clipboard.writeText(text).then(function () {
            showNotification('Đã sao chép vào clipboard!', 'success');
        });
    });

    // ==================== BULK ACTIONS ====================

    $('#checkAll').change(function () {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkActions();
    });

    $('.item-checkbox').change(function () {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('.item-checkbox:checked').length;
        if (checkedCount > 0) {
            $('.bulk-actions').show();
            $('.bulk-count').text(checkedCount);
        } else {
            $('.bulk-actions').hide();
        }
    }

    // ==================== EXPORT DATA ====================

    $('.export-btn').click(function () {
        var format = $(this).data('format');
        var table = $(this).data('table');
        showLoading();

        // Implement export logic here
        setTimeout(function () {
            hideLoading();
            showNotification('Đang xuất dữ liệu...', 'info');
        }, 1000);
    });

    // ==================== REAL-TIME UPDATES ====================

    // Polling for notifications (every 30 seconds)
    if ($('.badge-notification').length > 0) {
        setInterval(function () {
            // Fetch new notifications
            // $.get('/admin/notifications/count', function(data) {
            //     $('.badge-notification').text(data.count);
            // });
        }, 30000);
    }

    // ==================== CHART RESPONSIVE ====================

    $(window).resize(function () {
        if (window.Chart) {
            Chart.instances.forEach(function (chart) {
                chart.resize();
            });
        }
    });

});

// ==================== UTILITY FUNCTIONS ====================

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Slugify string
function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

// Loading overlay styles (injected dynamically)
const loadingStyles = `
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }
    </style>
`;
$('head').append(loadingStyles);
