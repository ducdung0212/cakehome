@props(['productId', 'stock' => 0, 'class' => ''])

<button type="button" 
        class="btn btn-primary-custom btn-sm add-to-cart-btn {{ $class }} {{ $stock <= 0 ? 'disabled' : '' }}" 
        onclick="addToCart({{ $productId }}, this)"
        data-product-id="{{ $productId }}"
        {{ $stock <= 0 ? 'disabled' : '' }}>
        
    <i class="bi bi-cart-plus"></i> 
    
    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    
    <span class="btn-text ms-1">{{ $stock > 0 ? '' : 'Hết hàng' }}</span>
</button>