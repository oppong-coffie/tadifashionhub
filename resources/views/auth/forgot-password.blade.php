@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="background: url('/images/background.jpg') no-repeat center center/cover;">
    <div class="card shadow-lg p-4 col-md-5 col-sm-8 bg-white bg-opacity-50" style="backdrop-filter: blur(10px); border-radius: 15px;">
        <div class="text-center">
            <h2 class="fw-bold">Forgot Password</h2>
            <p class="text-muted">Enter your email or phone number to receive an OTP</p>
        </div>

        <form method="POST" action="{{ route('password.otp') }}">
            @csrf
            <div class="mb-3">
                <input type="text" name="identifier" class="form-control rounded-pill p-2" placeholder="Enter phone or email" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill">Request OTP</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
        </div>
    </div>
</div>
@endsection
