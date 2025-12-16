@props(['productId' => 0, 'active' => false])

<button type="button" class="btn btn-light btn-sm wishlist-btn {{ $active ? 'active' : '' }}"
    onclick="toggleWishlist({{ $productId }}, this)" data-product-id="{{ $productId }}"
    title="{{ $active ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
    <i class="bi bi-heart{{ $active ? '-fill text-danger' : '' }}"></i>
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
