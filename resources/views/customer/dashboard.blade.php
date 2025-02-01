@extends('customer.layout')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@section('content')
    <div class="container">
        <div class="row text-white rounded" style="background-image: url('{{ asset('images/dress10.gif') }}'); background-size: cover; background-position: center;">
            <div class="col text-center mb-4">
                <h1 class="display-4">Welcome to Your Dashboard</h1>
                <p class="lead">Explore our latest fashion products!</p>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Loop through products and display each one -->
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $product['product_image']) }}" class="card-img-top" alt="{{ $product['product_name'] }}" style="object-fit: cover; width: 100%; height: 100%;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title font-weight-bold">{{ $product['product_name'] }}</h5>
                        <p class="card-text text-muted">${{ number_format($product['product_price'], 2) }}</p>
                        
                        <!-- Add to Cart Button (Triggers Modal) -->
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#productDetailModal-{{ $product['product_id'] }}">
                            <i class="fas fa-shopping-cart"></i> View Details
                        </button>
                    </div>
                </div>
            </div>            

        <!-- Product Detail Modal -->
<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal-{{ $product['product_id'] }}" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productModalLabel">{{ $product['product_name'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('storage/' . $product['product_image']) }}" class="img-fluid rounded shadow-sm" alt="{{ $product['product_name'] }}">
                    </div>

                    <div class="col-md-6">
                        <h4 class="text-primary">${{ number_format($product['product_price'], 2) }}</h4>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>

                        <!-- Add to Cart Form -->
                        <form action="{{ route('addtocart') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                            <input type="hidden" name="product_name" value="{{ $product['product_name'] }}">
                            <input type="hidden" name="product_image" value="{{ $product['product_image'] }}">
                            <input type="hidden" name="product_price" value="{{ $product['product_price'] }}">
                            <input type="hidden" name="designer_id" value="{{ $product['designer_id'] }}">
                            
                            <!-- Quantity Input -->
                            <div class="mt-3">
                                <label for="quantity-{{ $product['product_id'] }}" class="form-label fw-bold">Quantity:</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary decrement" type="button" data-target="{{ $product['product_id'] }}">-</button>
                                    <input type="number" class="form-control text-center quantity-input" name="quantity" id="quantity-{{ $product['product_id'] }}" value="1" min="1" data-price="{{ $product['product_price'] }}">
                                    <button class="btn btn-outline-secondary increment" type="button" data-target="{{ $product['product_id'] }}">+</button>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h5 class="fw-bold">Subtotal: $<span id="subtotal-{{ $product['product_id'] }}">{{ number_format($product['product_price'], 2) }}</span></h5>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="cartModalLabel">Your Shopping Cart</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cartItems as $item)
                                <tr>
                                    <td><img src="{{ asset('storage/' . $item->product_image) }}" width="50" alt="{{ $item->product_name }}" /></td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>${{ number_format($item->product_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->product_price * $item->quantity, 2) }}</td>
                                    <td>
                                        <!-- Optionally add a remove button or link -->
                                        <form action="{{ route('removeFromCart', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">Your cart is empty.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <h5 class="fw-bold me-auto">Total: $<span id="cartTotal">
                        {{ number_format($cartItems->sum(function($item) { return $item->product_price * $item->quantity; }), 2) }}
                    </span></h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                        {{-- <input type="hidden" name="user_id" value="{{ $product->customer_id }}"> --}}
                        {{-- <input type="hidden" name="total_amount" value="{{ number_format($cartItems->sum(function($item) { return $item->product_price * $item->quantity; }), 2) }}"> --}}
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </form>
                                    </div>
            </div>
        </div>
    </div>
     <!--END:: Cart Modal -->




            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function updateSubtotal(productId) {
                const quantityInput = document.getElementById("quantity-" + productId);
                const price = parseFloat(quantityInput.getAttribute("data-price"));
                let quantity = parseInt(quantityInput.value);
    
                // Ensure quantity is at least 1
                if (isNaN(quantity) || quantity < 1) {
                    quantity = 1;
                    quantityInput.value = 1;
                }
    
                // Calculate and update subtotal
                const subtotal = (quantity * price).toFixed(2);
                document.getElementById("subtotal-" + productId).textContent = subtotal;
            }
    
            // Handle quantity increment
            document.querySelectorAll(".increment").forEach(button => {
                button.addEventListener("click", function () {
                    const target = this.getAttribute("data-target");
                    const quantityInput = document.getElementById("quantity-" + target);
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updateSubtotal(target);
                });
            });
    
            // Handle quantity decrement
            document.querySelectorAll(".decrement").forEach(button => {
                button.addEventListener("click", function () {
                    const target = this.getAttribute("data-target");
                    const quantityInput = document.getElementById("quantity-" + target);
                    if (parseInt(quantityInput.value) > 1) {
                        quantityInput.value = parseInt(quantityInput.value) - 1;
                        updateSubtotal(target);
                    }
                });
            });
    
            // Handle manual quantity input change
            document.querySelectorAll(".quantity-input").forEach(input => {
                input.addEventListener("input", function () {
                    const target = this.id.split("-")[1];
                    updateSubtotal(target);
                });
            });
        });
    </script>
    

@endsection