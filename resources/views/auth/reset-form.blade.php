@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="position: relative;">
    <!-- Background Gradient with Overlay -->
    <div class="background-overlay"></div>
    <div class="card shadow-lg p-5 col-md-6 col-sm-8 bg-white bg-opacity-90" style="border-radius: 20px; position: relative; z-index: 1;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Reset Your Password</h2>
            <p class="text-muted">Please enter a new password to reset your account</p>
        </div>

        <!-- Error message with animation -->
        @if (session('error'))
            <div class="alert alert-danger fade-in mb-3">
                {{ session('error') }}
            </div>
        @endif

        <!-- Password Reset Form -->
        <form action="{{ route('reset') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <div class="mb-3">
                <input type="password" name="password" class="form-control rounded-pill p-3 shadow-sm" placeholder="New Password" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control rounded-pill p-3 shadow-sm" placeholder="Confirm Password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 rounded-pill mt-3 py-2 shadow-lg btn-hover">Update Password</button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-decoration-none text-primary">Remember your password? Login here</a>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Adding fade-in animation for error message */
    .fade-in {
        animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Custom hover effect for buttons */
    .btn-hover:hover {
        background-color: #007bff;
        transform: scale(1.05);
        box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.3);
    }

    /* Input fields styling with floating labels */
    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        border-color: #007bff;
    }

    /* Gradient background with a blur effect */
    .background-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #4e73df, #1cc88a);
        filter: blur(10px);
        opacity: 0.4;
        z-index: 0;
    }
</style>
@endpush
