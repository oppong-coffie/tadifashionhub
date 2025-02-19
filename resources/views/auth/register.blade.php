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
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-fashion btn-block mt-3">Register</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Already have an account? Login</a>
        </div>
    </div>
</div>
@endsection
