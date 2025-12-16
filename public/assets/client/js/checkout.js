// ========================================
// DELIVERY TIME PICKER WITH FLATPICKR
// ========================================
document.addEventListener('DOMContentLoaded', function () {
    // Xử lý checkbox "Giao ngay"
    const deliveryNowCheckbox = document.getElementById('delivery_now');
    const scheduledSection = document.getElementById('scheduled_delivery_section');
    const deliveryTimeInput = document.getElementById('delivery_time');

    if (deliveryNowCheckbox) {
        deliveryNowCheckbox.addEventListener('change', function () {
            if (this.checked) {
                scheduledSection.style.display = 'none';
                deliveryTimeInput.removeAttribute('required');
                deliveryTimeInput.value = '';
            } else {
                scheduledSection.style.display = 'block';
                deliveryTimeInput.setAttribute('required', 'required');
            }
        });
    }

    // Cấu hình Flatpickr
    if (deliveryTimeInput && typeof flatpickr !== 'undefined') {
        flatpickr("#delivery_time", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            time_24hr: true,

            // GIỚI HẠN GIỜ HOẠT ĐỘNG
            minTime: "08:00",
            maxTime: "20:00",

            // Locale tiếng Việt
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    longhand: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
                },
                months: {
                    shorthand: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                    longhand: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                },
            },

            // LOGIC CHẶN ĐẶT GẤP
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 0) return;

                let selectedDate = selectedDates[0];
                let now = new Date();

                // Nếu khách chọn ngày hôm nay
                if (selectedDate.getDate() === now.getDate() &&
                    selectedDate.getMonth() === now.getMonth() &&
                    selectedDate.getFullYear() === now.getFullYear()) {

                    // Cập nhật lại minTime động (hiện tại + 2 tiếng)
                    let hour = now.getHours() + 2;
                    if (hour < 8) hour = 8;
                    if (hour >= 20) {
                        alert('Đã quá giờ đặt hàng cho hôm nay. Vui lòng chọn ngày mai.');
                        instance.clear();
                        return;
                    }

                    instance.set('minTime', hour + ":00");
                } else {
                    // Nếu chọn ngày khác, reset về giờ mở cửa
                    instance.set('minTime', "08:00");
                }
            }
        });
    }
});

// ========================================
// DELIVERY METHOD TOGGLE
// ========================================
function toggleDeliveryFields() {
    const deliveryMethod = document.querySelector('input[name="delivery_method"]:checked').value;
    const deliveryTimeCard = document.getElementById('delivery_time_card');
    const shippingAddressCard = document.getElementById('shipping_address_card'); // Lấy card địa chỉ
    const pickupInfo = document.getElementById('pickup_info');
    const shippingFeeElement = document.getElementById('shipping-fee');
    const totalElement = document.getElementById('total');

    // Get current prices
    const subtotalText = document.getElementById('subtotal').textContent;
    const subtotal = parseInt(subtotalText.replace(/[^\d]/g, ''));

    if (deliveryMethod === 'pickup') {
        // === KHÁCH CHỌN NHẬN TẠI CỬA HÀNG ===
        pickupInfo.style.display = 'block';
        deliveryTimeCard.style.display = 'none'; // Ẩn phần chọn thời gian
        if (shippingAddressCard) shippingAddressCard.style.display = 'none'; // Ẩn phần địa chỉ

        // 1. Remove required attribute from delivery time fields
        const deliveryTimeInput = document.getElementById('delivery_time');
        if (deliveryTimeInput) {
            deliveryTimeInput.removeAttribute('required');
        }

        // 2. Remove required from address fields (cả saved và new)
        $('input[name="saved_address_id"]').prop('required', false);
        $('#new_address_section input, #new_address_section select').prop('required', false);

        // Update shipping fee
        shippingFeeElement.textContent = 'Miễn phí';
        shippingFeeElement.classList.add('text-success');

        // Recalculate total (no shipping fee)
        const total = subtotal;
        totalElement.textContent = total.toLocaleString('vi-VN') + ' VND';

    } else {
        // === KHÁCH CHỌN GIAO HÀNG TẬN NƠI ===
        pickupInfo.style.display = 'none';
        deliveryTimeCard.style.display = 'block'; // Hiện phần chọn thời gian
        if (shippingAddressCard) shippingAddressCard.style.display = 'block'; // Hiện phần địa chỉ

        // 1. Add required attribute back if not "delivery now"
        const deliveryNowCheckbox = document.getElementById('delivery_now');
        const deliveryTimeInput = document.getElementById('delivery_time');
        if (deliveryTimeInput && deliveryNowCheckbox && !deliveryNowCheckbox.checked) {
            deliveryTimeInput.setAttribute('required', 'required');
        }

        // 2. Kích hoạt lại required cho địa chỉ dựa trên tab đang chọn (saved hoặc new)
        const currentAddressType = $('input[name="address_type"]:checked').val();
        if (currentAddressType === 'saved') {
            $('input[name="saved_address_id"]').prop('required', true);
        } else {
            $('#new_address_section input[name="receiver_name"]').prop('required', true);
            $('#new_address_section input[name="receiver_phone"]').prop('required', true);
            $('#new_address_section select[name="province"]').prop('required', true);
            $('#new_address_section select[name="district"]').prop('required', true);
            $('#new_address_section select[name="ward"]').prop('required', true);
            $('#new_address_section input[name="address"]').prop('required', true);
        }

        // Update shipping fee
        const shippingFee = subtotal >= 500000 ? 0 : 30000;
        if (shippingFee === 0) {
            shippingFeeElement.textContent = 'Miễn phí';
            shippingFeeElement.classList.add('text-success');
        } else {
            shippingFeeElement.textContent = shippingFee.toLocaleString('vi-VN') + ' VND';
            shippingFeeElement.classList.remove('text-success');
        }

        // Recalculate total
        const total = subtotal + shippingFee;
        totalElement.textContent = total.toLocaleString('vi-VN') + ' VND';
    }
}

// ========================================
// ADDRESS SELECTION & VALIDATION
// ========================================
$(document).ready(function () {
    // Toggle between saved and new address
    $('input[name="address_type"]').change(function () {
        // Chỉ xử lý nếu đang hiển thị form địa chỉ (tức là delivery method = delivery)
        const deliveryMethod = $('input[name="delivery_method"]:checked').val();
        const isDelivery = deliveryMethod === 'delivery' || !deliveryMethod; // Default to delivery if undefined

        if ($(this).val() === 'saved') {
            $('#saved_addresses_section').slideDown();
            $('#new_address_section').slideUp();
            
            if (isDelivery) {
                // Make saved address fields required
                $('input[name="saved_address_id"]').prop('required', true);
                $('#new_address_section input, #new_address_section select').prop('required', false);
            }
        } else {
            $('#saved_addresses_section').slideUp();
            $('#new_address_section').slideDown();
            
            if (isDelivery) {
                // Make new address fields required
                $('input[name="saved_address_id"]').prop('required', false);
                $('#new_address_section input[name="receiver_name"]').prop('required', true);
                $('#new_address_section input[name="receiver_phone"]').prop('required', true);
                $('#new_address_section select[name="province"]').prop('required', true);
                $('#new_address_section select[name="district"]').prop('required', true);
                $('#new_address_section select[name="ward"]').prop('required', true);
                $('#new_address_section input[name="address"]').prop('required', true);
            }
        }
    });

    // Form validation
    $('form').submit(function (e) {
        const deliveryMethod = $('input[name="delivery_method"]:checked').val();

        // CHỈ VALIDATE ĐỊA CHỈ VÀ THỜI GIAN NẾU LÀ GIAO HÀNG TẬN NƠI
        if (deliveryMethod === 'delivery') {
            const addressType = $('input[name="address_type"]:checked').val();

            // 1. Validate địa chỉ giao hàng
            if (addressType === 'saved') {
                if (!$('input[name="saved_address_id"]:checked').length) {
                    e.preventDefault();
                    alert('Vui lòng chọn địa chỉ giao hàng!');
                    return false;
                }
            } else {
                // Validate new address fields
                const requiredFields = [{
                    name: 'receiver_name',
                    label: 'Người nhận'
                },
                {
                    name: 'receiver_phone',
                    label: 'Số điện thoại'
                },
                {
                    name: 'province',
                    label: 'Tỉnh/Thành phố'
                },
                {
                    name: 'district',
                    label: 'Quận/Huyện'
                },
                {
                    name: 'ward',
                    label: 'Phường/Xã'
                },
                {
                    name: 'address',
                    label: 'Địa chỉ cụ thể'
                }
                ];

                for (let field of requiredFields) {
                    const value = $('[name="' + field.name + '"]').val();
                    if (!value || value.trim() === '') {
                        e.preventDefault();
                        alert('Vui lòng nhập ' + field.label + '!');
                        $('[name="' + field.name + '"]').focus();
                        return false;
                    }
                }
            }

            // 2. Validate thời gian giao hàng
            const deliveryNow = $('input[name="delivery_now"]').is(':checked');
            const deliveryAt = $('input[name="delivery_at"]').val();

            if (!deliveryNow && (!deliveryAt || deliveryAt.trim() === '')) {
                e.preventDefault();
                alert('Vui lòng chọn thời gian giao hàng hoặc tích "Giao ngay khi có thể"!');
                $('input[name="delivery_at"]').focus();
                return false;
            }
        }

        // 3. Validate phương thức thanh toán (Luôn kiểm tra)
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        if (!paymentMethod) {
            e.preventDefault();
            alert('Vui lòng chọn phương thức thanh toán!');
            return false;
        }

        // All validation passed
        return true;
    });

    // Initialize on page load
    const initialAddressType = $('input[name="address_type"]:checked').val();
    const initialDeliveryMethod = $('input[name="delivery_method"]:checked').val();
    
    // Nếu là pickup thì không required gì cả
    if (initialDeliveryMethod === 'pickup') {
        $('input[name="saved_address_id"]').prop('required', false);
        $('#new_address_section input, #new_address_section select').prop('required', false);
    } else {
        // Nếu là delivery thì set required theo address type
        if (initialAddressType === 'saved') {
            $('#saved_addresses_section').show();
            $('#new_address_section').hide();
            $('#new_address_section input, #new_address_section select').prop('required', false);
            $('input[name="saved_address_id"]').prop('required', true);
        } else {
            $('#saved_addresses_section').hide();
            $('#new_address_section').show();
            $('input[name="saved_address_id"]').prop('required', false);
            $('#new_address_section input[name="receiver_name"]').prop('required', true);
            $('#new_address_section input[name="receiver_phone"]').prop('required', true);
            $('#new_address_section select[name="province"]').prop('required', true);
            $('#new_address_section select[name="district"]').prop('required', true);
            $('#new_address_section select[name="ward"]').prop('required', true);
            $('#new_address_section input[name="address"]').prop('required', true);
        }
    }
});