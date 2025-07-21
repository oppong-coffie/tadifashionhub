@extends('designer.layout')

@section('content')
    <?php
    // Get session data
    $user_id = session('user_id');
    
    ?>
    @if (session('success'))
        <div class="alert alert-success" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    <div class="container py-4" style="background-color: #e0f7fa;">
        <div class="row mb-4">
            <!-- Total Clothes Section -->
            <div class="col-md-4 mb-">
                <a class="text-decoration-none" href="{{ route('getproducts', ['user_id' => $user_id]) }}">
                    <div class="p-4 rounded shadow-sm hover-bg-green"
                        style="background: linear-gradient(135deg, #00796b, #48a999);">
                        <p class="text-white fw-bold">Total Clothes</p>
                        <h2 class="fw-bold text-white">{{$productCount}}</h2>
                    </div>
                </a>
            </div>

            <!-- Add New Fashion Section -->
            <div class="col-md-8 rounded" style="background-color: #ffffff;">
                <h4 class="text-primary mb-">Add New Fashion</h4>
                <form action="{{ route('sendproduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="designer_id" value="{{ $user_id }}">
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
                                placeholder="Fashion Image" required style="border-color: #b2ebf2;">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn w-100 bg-success"
                                style="background-color: #4caf50; color: white; border: none;">Add Fashion</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" style="background-color: #b2dfdb;">
            <!-- START:: Paid Items Section -->
            <div class="col-md-8 mb-4" style="background-color: #b2dfdb;">
                <h4 class="text-primary">Paid Items</h4>
                <table class="table table-striped table-hover" style="background-color: #ffffff; border-radius: 8px;">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Item</th>
                            <th scope="col">Amount Paid</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paidproducts as $paidproduct)
                            <tr>
                                <td>{{ $paidproduct->customer_name }}</td>
                                <td>{{ $paidproduct->product_name }}</td>
                                <td>{{ $paidproduct->product_price }}</td>
                                <td>
                                    <form action="{{ route('rejectproduct', $paidproduct->id) }}" method="POST">
                                        @csrf
                                        <!-- Form fields -->
                                        <button type="submit">Reject Product</button>
                                    </form>
                                        <form action="{{ route('servedproduct', $paidproduct->id) }}" method="POST">
                                        @csrf
                                        <!-- Form fields -->
                                        <button type="submit">Serve Product</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- END:: Paid Items Section -->


            <!-- Served and Rejected Items Section -->
            <div class="col-md-4">
                <!-- Served Items Section -->
                <div class="">
                    <h4 class="text-primary">Served Items</h4>
                    <table class="table table-striped table-hover" style="background-color: #ffffff; border-radius: 8px;">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servedproducts as $servedproduct)
                                <tr>
                                    <td>{{ $servedproduct->customer_name }}</td>
                                    <td>{{ $servedproduct->created_at }}</td>
                                    <td>{{ $servedproduct->product_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- START:: Rejected Items Section -->
                <div>
                    <h4 class="text-danger">Rejected Items</h4>
                    <table class="table table-striped table-hover" style="background-color: #ffffff; border-radius: 8px;">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Amount Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rejectedproducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->created_at }}</td>
                                    <td>{{ $product->product_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END:: Rejected Items Section -->


            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('successMessage').style.display = 'none';
        }, 30000); // 30 seconds
    </script>
        </style>
        <script>
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 10000); // 30 seconds
        </script>
@endsection
