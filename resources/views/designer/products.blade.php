@extends('designer.layout')

@section('content')
    <?php
    // Get session data
    $user_id = session('user_id');
    ?>
    <!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body with Form -->
            <div class="modal-body">
                <form action="{{ route('updateproduct') }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden Field for Product ID -->
                    <input type="hidden" name="product_id" id="product-id">
                    
                    <!-- Product Name and Price Fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="fashion-name" name="product_name"
                                placeholder="Fashion Name" required style="border-color: #b2ebf2;">
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" id="fashion-price" name="product_price"
                                placeholder="Price" required style="border-color: #b2ebf2;">
                        </div>
                    </div>
    
                    <!-- Image Upload and Submit Button -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="file" class="form-control" id="fashion-image" name="product_image"
                                placeholder="Fashion Image" style="border-color: #b2ebf2;">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn w-100 bg-success"
                                style="background-color: #4caf50; color: white; border: none;">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <div class="container" style="background-color: #f1f8f7;">

        <!-- Store Heading Section -->
        <div class="text-center">
            <h2 style="font-size: 2rem; font-weight: bold; color: #00796b;">My Fashion Store</h2>
            <p style="font-size: 1rem; color: #666;">Here are the items for your review.</p>
        </div>

        <!-- Table Section -->
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card shadow-lg" style="background-color: #ffffff; border-radius: 20px; border: none;">

                    <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 20px 20px;">
                        <table class="table table-striped table-hover" style="border-radius: 8px; margin-bottom: 0;">
                            <thead class="thead-dark" style="background-color: #00796b;">
                                <tr>
                                    <th scope="col" class="text-center">Image</th>
                                    <th scope="col" class="text-center">Fashion Name</th>
                                    <th scope="col" class="text-center">Price</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <!-- Increased image size, centered and rounded -->
                                            <img src="{{ asset('storage/' . $product['product_image']) }}"
                                                alt="Product Image" class="rounded-circle"
                                                style="width: 90px; height: 90px; object-fit: cover;">
                                        </td>
                                        <td class="text-center">
                                            <!-- Fashion name with improved font size and weight -->
                                            <span style="font-size: 1.1rem; font-weight: 600; color: #333;">
                                                {{ $product['product_name'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Price with bold and distinct color -->
                                            <span style="font-size: 1.1rem; font-weight: bold; color: #00796b;">
                                                ${{ number_format($product['product_price'], 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Buttons with trigger-->
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                            onclick="editProduct({{ $product->product_id }}, '{{ $product->product_name }}', {{ $product->product_price }}, '{{ $product->product_image }}')">
                                            Edit
                                        </button>
                                        

                                        <form action="{{ route('deleteproduct', $product->product_id) }}" method="POST"
                                            id="delete-form-{{ $product->product_id }}">
                                          @csrf
                                          @method('DELETE') <!-- Spoof DELETE method -->
                                          <button type="button" class="btn btn-danger"
                                                  onclick="confirmDelete({{ $product->product_id }})">Delete</button>
                                      </form>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
    function confirmDelete(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        // If confirmed, submit the form
        document.getElementById('delete-form-' + productId).submit();
    }
}


        function editProduct(id, name, price, image) {
        document.getElementById('product-id').value = id;
        document.getElementById('fashion-name').value = name;
        document.getElementById('fashion-price').value = price;

        // Optional: Handle image preview if necessary
        // Example: Display the current image in an img tag (if applicable)
        if (image) {
            const imgPreview = document.createElement('img');
            imgPreview.src = `/path/to/images/${image}`; // Adjust this path as necessary
            imgPreview.alt = 'Current Product Image';
            imgPreview.style = 'max-width: 100%; margin-top: 10px;';
            document.getElementById('fashion-image').parentElement.appendChild(imgPreview);
        }
    }
    </script>
@endsection
