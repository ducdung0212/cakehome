// Product Detail Page Scripts
function changeImage(thumbnail) {
    document.getElementById('mainImage').src = thumbnail.src.replace('w=200', 'w=800');
    document.querySelectorAll('.product-thumbnail').forEach(img => {
        img.classList.remove('active');
    });
    thumbnail.classList.add('active');
}

function increaseQty() {
    let qty = document.getElementById('quantity');
    qty.value = parseInt(qty.value) + 1;
}

function decreaseQty() {
    let qty = document.getElementById('quantity');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

function addToWishlist(productId) {
    showSuccess('Đã thêm vào yêu thích', 'Sản phẩm đã được thêm vào danh sách yêu thích');
}

function submitReview() {
    const rating = document.querySelector('input[name="rating"]:checked');
    const comment = document.getElementById('reviewComment').value.trim();

    if (!rating) {
        showWarning('Chưa đánh giá', 'Vui lòng chọn số sao đánh giá');
        return;
    }

    if (!comment) {
        showWarning('Chưa nhập nội dung', 'Vui lòng nhập nội dung đánh giá');
        return;
    }

    Swal.fire({
        title: 'Gửi đánh giá',
        text: 'Bạn có muốn gửi đánh giá này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#8B4513',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Gửi',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Simulate API call
            Toast.fire({
                icon: 'success',
                title: 'Cảm ơn bạn!',
                text: 'Đánh giá của bạn đã được gửi thành công'
            });

            // Clear form
            document.getElementById('reviewComment').value = '';
            const checkedRadio = document.querySelector('input[name="rating"]:checked');
            if (checkedRadio) checkedRadio.checked = false;
        }
    });
}
