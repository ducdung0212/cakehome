// Cấu hình headers mặc định cho mọi request
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/**
 * 1. Hàm Thêm vào giỏ hàng
 */
function addToCart(productId, btnElement) {
    if (!btnElement) return;

    // UI Loading
    const icon = btnElement.querySelector('.bi');
    const spinner = btnElement.querySelector('.spinner-border');

    btnElement.disabled = true;
    if (icon) icon.classList.add('d-none');
    if (spinner) spinner.classList.remove('d-none');

    // Gọi AJAX
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1 // Mặc định là 1, có thể lấy từ input nếu ở trang chi tiết
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                updateCartCount(data.cart_count); // Cập nhật số trên Header
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => {
            console.error(err);
            toastr.error('Lỗi kết nối server');
        })
        .finally(() => {
            // Reset UI
            btnElement.disabled = false;
            if (icon) icon.classList.remove('d-none');
            if (spinner) spinner.classList.add('d-none');
        });
}

/**
 * 2. Hàm Xóa khỏi giỏ hàng
 */
function removeFromCart(productId, btnElement) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

    fetch('/cart/remove', {
        method: 'POST', // Hoặc DELETE tuỳ route bạn định nghĩa
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ product_id: productId })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                updateCartCount(data.cart_count);

                // Xóa dòng sản phẩm đó khỏi giao diện (DOM)
                const rowItem = document.getElementById(`cart-item-${productId}`);
                if (rowItem) {
                    rowItem.remove();
                }

                // Nếu giỏ hàng trống thì reload để hiện màn hình "Giỏ hàng trống"
                if (data.cart_count === 0) {
                    location.reload();
                }

                // Cập nhật lại tổng tiền (Nếu có element hiển thị tổng)
                if (data.total_amount && document.getElementById('cart-total')) {
                    document.getElementById('cart-total').innerText = data.total_amount;
                }
            }
        });
}

/**
 * Helper: Cập nhật số lượng trên icon giỏ hàng header
 */
function updateCartCount(count) {
    const el = document.getElementById('cart-count'); // ID của badge trên header
    if (el) el.innerText = count;
}

/**
 * 3. Hàm Cập nhật số lượng sản phẩm trong giỏ hàng
 */
function updateQuantity(productId, currentQty, action, maxStock) {
    let newQty = currentQty;

    if (action === 'increase') {
        if (currentQty >= maxStock) {
            toastr.warning('Đã đạt số lượng tối đa trong kho!');
            return;
        }
        newQty = currentQty + 1;
    } else if (action === 'decrease') {
        if (currentQty <= 1) {
            toastr.warning('Số lượng tối thiểu là 1!');
            return;
        }
        newQty = currentQty - 1;
    }

    // Cập nhật UI ngay lập tức
    const qtyInput = document.getElementById(`qty-${productId}`);
    if (qtyInput) {
        qtyInput.value = newQty;
    }

    // Gọi API cập nhật
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQty
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                updateCartCount(data.cart_count);
                // Có thể reload trang để cập nhật tổng tiền
                // location.reload();
            } else {
                // Rollback nếu lỗi
                if (qtyInput) {
                    qtyInput.value = currentQty;
                }
                toastr.error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => {
            console.error(err);
            // Rollback nếu lỗi
            if (qtyInput) {
                qtyInput.value = currentQty;
            }
            toastr.error('Lỗi kết nối server');
        });
}

/**
 * 4. Hàm Xóa toàn bộ giỏ hàng
 */
function clearCart() {
    if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) return;

    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                updateCartCount(0);
                // Reload trang để hiển thị "Giỏ hàng trống"
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                toastr.error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(err => {
            console.error(err);
            toastr.error('Lỗi kết nối server');
        });
}