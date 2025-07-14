<?php
// Get session data
// $user_id = session('user_id');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Fashion Shop Dashboard</title>
    <style>
        .hover-bg-green:hover {
            background: linear-gradient(135deg, #004d40, #00695c);
            cursor: pointer;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="" style="background-color: #e0f7fa;">
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Fashion Shop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/designer/dashboard">
                            <i class="fa fa-home fa-2xl"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                            <i class="fa-solid fa-cart-arrow-down fa-shake fa-2xl mt-2" data-bs-toggle="modal" data-bs-target="#cartModal"></i>                        
                    </li>

                    <!-- Profile Section -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('images/user.png')}}" alt="Profile Picture"
                                class="rounded-circle" width="50" height="30">
                            {{ $user_name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
