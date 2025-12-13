<!-- Wishlist Button Component -->
<button type="button" class="btn btn-light btn-sm wishlist-btn {{ isset($active) && $active ? 'active' : '' }}"
    onclick="toggleWishlist({{ $productId ?? 0 }}, this)" data-product-id="{{ $productId ?? 0 }}"
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
