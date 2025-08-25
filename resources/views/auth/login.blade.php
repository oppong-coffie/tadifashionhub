@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="form-container col-md-6">
        <div class="text-center mb-4">
            <img src="/images/background8.jpeg" class="rounded-circle" height="95" alt="Logo" width="100">
            <h3 class="mt-2">Login</h3>
        </div>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            {{-- Show global error messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            {{-- Email field --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required
                >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            {{-- Password field --}}
            <div class="form-group"> 
                <label for="password">Password</label>
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    required
                >
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        
            <button type="submit" class="btn btn-fashion btn-block mt-3">Login</button>
        </form>
        
        

        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password</a>
        </div>

        <div class="text-center mt-3">
            <span>Don't have an account?</span> 
            <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Register</a>
        </div>
    </div>
</div>
@endsection
