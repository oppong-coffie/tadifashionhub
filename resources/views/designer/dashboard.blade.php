@extends('designer.layout')

@section('content')
    <?php $user_id = session('user_id'); ?>

    @if (session('success'))
        <div class="alert alert-success animate__animated animate__fadeInDown" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    <div class="container py-4" style="background-color: #e0f7fa;">
        <div class="row mb-4">
            <!-- Total Clothes Section -->
            <div class="col-md-4 motion-card motion-btn">
                <a class="text-decoration-none motion-card" href="{{ route('getproducts', ['user_id' => $user_id]) }}">
                    <div data-aos="zoom-in" class="p-4 rounded shadow-sm"
                        style="background: linear-gradient(135deg, #00796b, #48a999);">
                        <p class="text-white fw-bold">Total Clothes</p>
                        <h2 class="fw-bold text-white">{{ $productCount }}</h2>
                    </div>
                </a>
            </div>

            <!-- Add New Fashion Section -->
            <div class="col-md-8 rounded motion-card" style="background-color: #ffffff;" data-aos="fade-left">
                <h4 class="text-primary">Add New Fashion</h4>
                <form action="{{ route('sendproduct') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="designer_id" value="{{ $user_id }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control motion-input" name="product_name"
                                placeholder="Fashion Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control motion-input" name="product_price"
                                placeholder="Price" required>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="file" class="form-control motion-input" name="product_image" required>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn w-100 btn-success motion-btn">Add Fashion</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Paid Items -->
        <div class="row" style="background-color: #b2dfdb;">
            <div class="col-md-8 mb-4 motion-card" data-aos="fade-up">
                <h4 class="text-primary">Paid Items</h4>
                <table class="table table-striped table-hover bg-white rounded motion-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Customer Name</th>
                            <th>Item</th>
                            <th>Amount Paid</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paidproducts as $paidproduct)
                            <tr>
                                <td>{{ $paidproduct->customer_name }}</td>
                                <td>{{ $paidproduct->product_name }}</td>
                                <td>{{ $paidproduct->product_price }}</td>
                                <td class="d-flex gap-2">
                                    <form action="{{ route('rejectproduct', $paidproduct->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm motion-btn">Reject ⚠️</button>
                                    </form>
                                    <form action="{{ route('servedproduct', $paidproduct->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm motion-btn">Serve ✅</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Served & Rejected Items -->
            <div class="col-md-4">
                <div data-aos="fade-right" class="motion-card">
                    <h4 class="text-primary">Served Items</h4>
                    <table class="table table-striped table-hover bg-white rounded motion-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Amount Paid</th>
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

                <div data-aos="fade-right" data-aos-delay="200" class="motion-card">
                    <h4 class="text-danger">Rejected Items</h4>
                    <table class="table table-striped table-hover bg-white rounded motion-table">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Date</th>
                                <th>Amount Paid</th>
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
            </div>
        </div>
    </div>

    <!-- Motion styles -->
    <style>
        .motion-card {
            transition: all 0.3s ease-in-out;
        }

        .motion-card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        }

        .motion-btn {
            transition: all 0.2s ease-in-out;
        }

        .motion-btn:hover {
            transform: scale(1.05);
        }

        .motion-btn:active {
            transform: scale(0.95);
        }

        .motion-input:focus {
            transform: scale(1.02);
            border-color: #00796b !important;
            box-shadow: 0 0 8px rgba(0, 121, 107, 0.4);
        }

        .motion-table tbody tr {
            transition: all 0.2s ease;
        }

        .motion-table tbody tr:hover {
            background-color: #f1fdfd;
            transform: scale(1.01);
        }
    </style>

    <!-- Scripts -->
    <script>
        setTimeout(() => {
            let msg = document.getElementById('successMessage');
            if (msg) msg.style.display = 'none';
        }, 10000);
    </script>

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
@endsection
