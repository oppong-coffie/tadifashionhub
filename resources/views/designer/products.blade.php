@extends('designer.layout')

@section('content')
    <?php $user_id = session('user_id'); ?>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('updateproduct') }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="product_id" id="product-id">

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

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="file" class="form-control" id="fashion-image" name="product_image"
                                    style="border-color: #b2ebf2;">
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn w-100 btn-success hover-scale">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Section -->
    <div class="container py-4 mb-32" style="background-color: #f1f8f7;">
        <div class="text-center mb-4" data-aos="fade-down">
            <h2 style="font-size: 2rem; font-weight: bold; color: #00796b;">My Fashion Store</h2>
            <p data-aos="zoom-in" data-aos-delay='2000' style="font-size: 1rem; color: #666;">Here are the items for your review.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card shadow-lg hover-scale" data-aos="zoom-in-up" style="border-radius: 20px; border: none;">
                    <div class="card-body" style="background-color: #f8f9fa; border-radius: 0 0 20px 20px;">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="thead-dark" style="background-color: #00796b;" data-aos="fade-down">
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Fashion Name</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ $product['product_image'] }}"
                                                alt="Product Image"
                                                class="rounded-circle hover-scale"
                                                style="width: 90px; height: 90px; object-fit: cover;">
                                        </td>
                                        <td class="text-center">
                                            <span style="font-size: 1.1rem; font-weight: 600; color: #333;">
                                                {{ $product['product_name'] }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span style="font-size: 1.1rem; font-weight: bold; color: #00796b;">
                                                ${{ number_format($product['product_price'], 2) }}
                                            </span>
                                        </td>
                                        <td class="justify-content-end gap-2 d-flex">
                                            <button type="button" class="btn btn-warning hover-scale"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editProductModal"
                                                onclick="editProduct({{ $product->product_id }}, '{{ $product->product_name }}', {{ $product->product_price }}, '{{ $product->product_image }}')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form action="{{ route('deleteproduct', $product->product_id) }}" method="POST" id="delete-form-{{ $product->product_id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger hover-scale"
                                                    onclick="confirmDelete({{ $product->product_id }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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

    <!-- Scripts -->
    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('delete-form-' + productId).submit();
            }
        }

        function editProduct(id, name, price, image) {
            document.getElementById('product-id').value = id;
            document.getElementById('fashion-name').value = name;
            document.getElementById('fashion-price').value = price;

            if (image) {
                const imgPreview = document.createElement('img');
                imgPreview.src = `/path/to/images/${image}`;
                imgPreview.alt = 'Current Product Image';
                imgPreview.style = 'max-width: 100%; margin-top: 10px;';
                document.getElementById('fashion-image').parentElement.appendChild(imgPreview);
            }
        }
    </script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
  duration: 1000,
  once: true,
  offset: 50 // triggers earlier (default is 120)
});
        });
    </script>
    <style>
        /* Universal hover + tap effects */
        .hover-scale {
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }
        .hover-scale:active {
            transform: scale(0.95);
        }
        .motion-card {
            transition: all 0.3s ease-in-out;
        }
        html, body {
  min-height: 100vh;
}

    </style>
@endsection
