/**
 * Wishlist Toggle Function - AJAX Implementation
 * Toggles product in/out of user's wishlist without page reload
 */
function toggleWishlist(productId, button) {
    // Prevent double clicks
    if (button.disabled) return;
    button.disabled = true;

    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Show loading state
    const icon = button.querySelector('i');
    const originalClass = icon.className;
    icon.className = 'bi bi-arrow-repeat'; // Loading spinner

    // Make AJAX request
    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update button state based on action
                if (data.action === 'added') {
                    button.classList.add('active');
                    icon.className = 'bi bi-heart-fill text-danger';
                    button.title = 'Xóa khỏi yêu thích';

                    // Update wishlist count in header
                    updateWishlistCount(1);

                    // Show success message
                    toastr.success(data.message);
                } else if (data.action === 'removed') {
                    button.classList.remove('active');
                    icon.className = 'bi bi-heart';
                    button.title = 'Thêm vào yêu thích';

                    // Update wishlist count in header
                    updateWishlistCount(-1);

                    // If we're on the wishlist page, reload to update pagination
                    const wishlistItem = document.getElementById(`wishlist-item-${productId}`);
                    if (wishlistItem) {
                        // Show success message first
                        toastr.success(data.message);

                        // Animate removal
                        wishlistItem.style.opacity = '0';
                        wishlistItem.style.transform = 'scale(0.8)';

                        // Reload page after animation to update pagination
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);

                        // Return early to avoid showing toast again
                        return;
                    }
                }
            } else {
                // Restore original state on failure
                icon.className = originalClass;
                toastr.error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restore original state
            icon.className = originalClass;
            toastr.error('Bạn cần đăng nhập để thực hiện chức năng này');
        })
        .finally(() => {
            // Re-enable button
            button.disabled = false;
        });
}

/**
 * Update Wishlist Count in Header
 * @param {number} change - The change amount (+1 for add, -1 for remove)
 */
function updateWishlistCount(change) {
    const countElement = document.getElementById('wishlist-count');
    if (countElement) {
        const currentCount = parseInt(countElement.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        countElement.textContent = newCount;

        // Add pulse animation
        countElement.style.animation = 'none';
        setTimeout(() => {
            countElement.style.animation = 'pulse 0.3s ease';
        }, 10);
    }
}

// Add pulse animation style for wishlist count
if (!document.querySelector('#wishlist-count-animation')) {
    const style = document.createElement('style');
    style.id = 'wishlist-count-animation';
    style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }
        
        /* Wishlist item animation */
        [id^="wishlist-item-"] {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
    `;
    document.head.appendChild(style);
}
