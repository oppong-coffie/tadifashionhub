
@extends('customer.layout')

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
                        <form action="{{route('addToCart', [
                        'designer_id': 'designer_id'
                        'customer_id': 'customer_id'
                        'product_image': 'product_image'
                        'product_price': 'product_price'
                        'product_name': 'product_name'
                        'quantity': 'quantity'
                        ])}}" method="post">
                            <button type="submit">Add</button>
                        </form>
                    
                    </div>
                </div>
            </div>            
            @endforeach
        </div>
    </div>
    
    @endsection

