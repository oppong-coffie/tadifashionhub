@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="form-container col-md-6">
        <div class="text-center mb-4">
            <img class="rounded-circle" src="/images/background8.jpeg" alt="Logo" height="90" width="100">
            <h3 class="mt-2">Register</h3>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
        
            <div class="form-group">
                <label for="role">Role</label>
                <select type="text" class="form-control" id="role" name="role" required>
                    <option value="">--select--</option>
                    <option value="designer">Designer</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
        
           {{-- Password field --}}
<div class="form-group position-relative">
    <label for="password">Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" required>
        <div class="input-group-append">
            <span class="input-group-text" onclick="togglePassword('password', 'togglePasswordIcon1')" style="cursor:pointer;">
                <i class="fa fa-eye" id="togglePasswordIcon1"></i>
            </span>
        </div>
    </div>
    @error('password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

{{-- Confirm Password field --}}
<div class="form-group position-relative">
    <label for="password-confirm">Confirm Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
        <div class="input-group-append">
            <span class="input-group-text" onclick="togglePassword('password-confirm', 'togglePasswordIcon2')" style="cursor:pointer;">
                <i class="fa fa-eye" id="togglePasswordIcon2"></i>
            </span>
        </div>
    </div>
    @error('password_confirmation')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
        
            <button type="submit" class="btn btn-fashion btn-block mt-3">Register</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>
    </div>
</div>
<script>
    document.querySelector("form").addEventListener("submit", function(e) {
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("password-confirm").value;
    
        if (password !== confirmPassword) {
            e.preventDefault(); // stop form submission
            alert("Passwords do not match!");
        }
    });
    
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    </script>
@endsection
