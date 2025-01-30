<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register page
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Send reistration form to database
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'in:customer,designer', // Ensures only valid roles are accepted
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role ?? 'customer', // Default role as 'customer'
            'password' => Hash::make($request->password),
        ]);
    
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    // Show login page
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // START:: FUNCTION TO LOGIN
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Store the user ID in the session
            $request->session()->put('user_id', $user->id);
    
            // Redirect based on role
            switch ($user->role) {
                case 'designer':
                    return redirect()->route('designer.dashboard');
    
                case 'customer':
                    return redirect()->route('customer.dashboard')->with('success', 'Welcome, Customer!');
    
                default:
                    \Log::warning('Unexpected user role: ' . $user->role);
                    return redirect()->route('dashboard')->with('success', 'You are logged in!');
            }
        }
    
        // Return with errors if authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    // END:: FUNCTION TO LOGIN

    

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
