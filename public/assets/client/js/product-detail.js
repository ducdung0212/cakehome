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

// Character counter for review comment
document.addEventListener('DOMContentLoaded', function () {
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });
    }

    // Review form submission
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitReviewBtn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang gửi...';

            const formData = new FormData(this);

            fetch('/reviews', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    // Check if response is ok
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Đánh giá của bạn đã được gửi và đang chờ duyệt!');

                        // Reset form
                        reviewForm.reset();
                        document.getElementById('charCount').textContent = '0';

                        // Reload page after 2 seconds to show updated reviews
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorMessage = '';
                            for (let field in data.errors) {
                                errorMessage += data.errors[field].join(', ') + '<br>';
                            }
                            toastr.error(errorMessage || data.message);
                        } else {
                            toastr.error(data.message || 'Có lỗi xảy ra. Vui lòng thử lại!');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.message) {
                        toastr.error(error.message);
                    } else if (error.errors) {
                        let errorMessage = '';
                        for (let field in error.errors) {
                            errorMessage += error.errors[field].join(', ') + ' ';
                        }
                        toastr.error(errorMessage);
                    } else {
                        toastr.error('Có lỗi xảy ra. Vui lòng thử lại!');
                    }
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    }
});

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
