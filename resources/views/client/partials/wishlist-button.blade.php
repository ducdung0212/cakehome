<!-- Wishlist Button Component -->
<button class="btn btn-light btn-sm wishlist-btn {{ isset($active) && $active ? 'active' : '' }}" 
        onclick="toggleWishlist({{ $productId ?? 0 }})" 
        title="{{ isset($active) && $active ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
    <i class="bi bi-heart{{ isset($active) && $active ? '-fill text-danger' : '' }}"></i>
</button>

<style>
.wishlist-btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    border: 1px solid #e0e0e0;
}

.wishlist-btn:hover {
    background: #fff;
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.wishlist-btn.active i {
    animation: heartBeat 0.5s;
}

.wishlist-btn i {
    font-size: 1.1rem;
}
</style>

<script>
function toggleWishlist(productId) {
    const btn = event.currentTarget;
    const icon = btn.querySelector('i');
    const isActive = icon.classList.contains('bi-heart-fill');
    
    if (isActive) {
        // Remove from wishlist
        icon.classList.remove('bi-heart-fill', 'text-danger');
        icon.classList.add('bi-heart');
        console.log('Removed product ' + productId + ' from wishlist');
        // AJAX call here
        updateWishlistCount(-1);
    } else {
        // Add to wishlist
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill', 'text-danger', 'wishlist-heart-animation');
        console.log('Added product ' + productId + ' to wishlist');
        // AJAX call here
        updateWishlistCount(1);
        
        // Remove animation class after animation ends
        setTimeout(() => {
            icon.classList.remove('wishlist-heart-animation');
        }, 500);
    }
}

function updateWishlistCount(change) {
    const badge = document.querySelector('.nav-link[href="/wishlist"] .cart-badge');
    if (badge) {
        const currentCount = parseInt(badge.textContent) || 0;
        const newCount = Math.max(0, currentCount + change);
        badge.textContent = newCount;
    }
}
</script>
