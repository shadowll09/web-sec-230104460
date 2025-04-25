@extends('layouts.master')
@section('title', $product->name)
@section('content')

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Product Images Column -->
                <div class="col-md-6">
                    <div class="position-relative">
                        <!-- Main product image -->
                        <img id="mainProductImage" src="{{ $product->getMainPhotoUrl() }}" class="img-fluid rounded-start" style="width: 100%; height: 400px; object-fit: cover;" alt="{{ $product->name }}">
                        
                        <!-- In stock / Out of stock badge -->
                        <div class="position-absolute top-0 end-0 p-3">
                            @if($product->stock_quantity > 0)
                                <span class="badge bg-success">In Stock</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </div>
                        
                        <!-- Theme-colored overlay effect -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-start" style="background: linear-gradient(135deg, rgba(var(--theme-gradient-start-rgb), 0.1) 0%, rgba(var(--theme-gradient-end-rgb), 0.2) 100%);"></div>
                    </div>
                    
                    <!-- Thumbnail gallery -->
                    @if($product->additional_photos && count($product->getAllPhotoUrls()) > 1)
                    <div class="d-flex mt-3 px-3 pb-3 overflow-auto">
                        @foreach($product->getAllPhotoUrls() as $index => $photoUrl)
                        <div class="me-2">
                            <img src="{{ $photoUrl }}" class="product-thumbnail rounded cursor-pointer {{ $index === 0 ? 'active' : '' }}" 
                                 style="width: 80px; height: 80px; object-fit: cover; border: 2px solid transparent;" 
                                 data-url="{{ $photoUrl }}" alt="Product thumbnail">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                
                <!-- Product Details Column -->
                <div class="col-md-6">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="mb-0">{{ $product->name }}</h1>
                            <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
                        </div>
                        
                        <p class="text-muted mb-2">{{ $product->code }} | {{ $product->model }}</p>
                        
                        <div class="my-4 py-2 px-3 rounded" style="background-color: rgba(var(--theme-primary-rgb), 0.05); border-left: 3px solid var(--theme-primary);">
                            <p class="mb-0">{{ $product->description }}</p>
                        </div>
                        
                        <!-- Stock information -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Availability:</span>
                                @if($product->stock_quantity > 10)
                                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>In Stock ({{ $product->stock_quantity }} available)</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="text-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i>Low Stock (Only {{ $product->stock_quantity }} left)</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Out of Stock</span>
                                @endif
                            </div>
                        </div>
                        
                        @auth
                            @if(auth()->user()->hasRole('Customer'))
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="input-group me-3" style="width: 140px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                                   class="form-control text-center quantity-input">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase" data-max="{{ $product->stock_quantity }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-gradient flex-grow-1 add-to-cart-btn" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                                            <i class="bi bi-cart-plus me-2"></i>
                                            {{ $product->stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
                                        </button>
                                    </div>
                                </form>
                            @endif
                            
                            @if(auth()->user()->hasAnyRole(['Admin', 'Employee']))
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Stock Management</span>
                                    <div>
                                        <a href="{{ route('products_edit', $product) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit Product
                                        </a>
                                        @if(auth()->user()->hasPermissionTo('delete_products'))
                                            <a href="{{ route('products_delete', $product) }}" class="btn btn-outline-danger ms-2"
                                               onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endauth
                        
                        @guest
                            <div class="alert alert-themed">
                                <i class="bi bi-info-circle me-2"></i>
                                Please <a href="{{ route('login') }}" class="text-white fw-bold">login</a> to purchase this product.
                            </div>
                        @endguest
                        
                        <!-- Features section -->
                        <div class="mt-4">
                            <h5 class="mb-3">Product Features</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-shield-check me-2" style="color: var(--theme-primary);"></i>
                                    <span>Premium quality guarantee</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-truck me-2" style="color: var(--theme-primary);"></i>
                                    <span>Fast shipping available</span>
                                </li>
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-arrow-repeat me-2" style="color: var(--theme-primary);"></i>
                                    <span>30-day return policy</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related products section (placeholder) -->
    <div class="mt-5">
        <h2 class="mb-4">Related Products</h2>
        <div class="row">
            <!-- Placeholders for related products would go here -->
            <div class="col-12 text-center py-5">
                <p class="text-muted">Feature coming soon!</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity buttons
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                const action = this.dataset.action;

                if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                } else if (action === 'increase') {
                    const max = parseInt(this.dataset.max);
                    if (currentValue < max) {
                        input.value = currentValue + 1;
                    }
                }
            });
        });
        
        // Product thumbnail gallery
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        const mainImage = document.getElementById('mainProductImage');
        
        if (thumbnails.length > 0 && mainImage) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.dataset.url;
                    
                    // Update active state
                    thumbnails.forEach(t => t.style.borderColor = 'transparent');
                    this.style.borderColor = 'var(--theme-primary)';
                });
            });
            
            // Set first thumbnail as active
            if (thumbnails[0]) {
                thumbnails[0].style.borderColor = 'var(--theme-primary)';
            }
        }
        
        // Add RGB variables for gradients
        function createRgbVars() {
            const root = document.documentElement;
            const primaryColor = getComputedStyle(root).getPropertyValue('--theme-gradient-start').trim();
            const secondaryColor = getComputedStyle(root).getPropertyValue('--theme-gradient-end').trim();
            
            // Helper to convert hex to rgb
            function hexToRgb(hex) {
                // Default fallback
                if (!hex || hex === '') return '74, 108, 247';
                
                // Remove # if present
                hex = hex.replace('#', '');
                
                // Convert to RGB
                const r = parseInt(hex.substring(0, 2), 16);
                const g = parseInt(hex.substring(2, 4), 16);
                const b = parseInt(hex.substring(4, 6), 16);
                
                return `${r}, ${g}, ${b}`;
            }
            
            // Set the RGB variables
            root.style.setProperty('--theme-gradient-start-rgb', hexToRgb(primaryColor));
            root.style.setProperty('--theme-gradient-end-rgb', hexToRgb(secondaryColor));
            root.style.setProperty('--theme-primary-rgb', hexToRgb(primaryColor));
        }
        
        // Call once on load and whenever the theme changes
        createRgbVars();
        
        // Watch for theme changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && 
                   (mutation.attributeName === 'data-theme' || 
                    mutation.attributeName === 'data-color-theme')) {
                    createRgbVars();
                }
            });
        });
        
        observer.observe(document.documentElement, { 
            attributes: true, 
            attributeFilter: ['data-theme', 'data-color-theme'] 
        });
    });
</script>
@endpush

@endsection 