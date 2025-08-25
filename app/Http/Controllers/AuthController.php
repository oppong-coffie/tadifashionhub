<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\PasswordResetModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


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
                    return redirect()->route('customer.dashboard')->with('success', "Welcome {$user['name']}");
    
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

        //  Show Password reset request OTP form
        public function showRequestForm()
        {
            return view('auth.forgot-password');
        }

         //  Handle OTP request identifier
         public function requestOTP(Request $request)
         {
             $request->validate(['identifier' => 'required']);
         
             // Find user by email or phone
             $user = User::where('email', $request->identifier)
                         ->orWhere('phone', $request->identifier)
                         ->first();
         
             if (!$user) {
                 return back()->with('error', 'User not found');
             }
             https://api.openai.com/v1/chat/completions
             // Generate a 4-digit OTP
             $otp = rand(1000, 9999);
         
             // SMS API Variables
             $key = 'd97868cc69d36af20e76';
             $sender = 'TadiFashion';
             $to = $user->phone;
             $message = "Your OTP is: $otp";
         
             // Send SMS using NotifyGH API
             $url = "https://sms.smsnotifygh.com/smsapi?key=$key&to=$to&msg=" . urlencode($message) . "&sender_id=$sender";
         
             // Send HTTP request
             $response = Http::get($url);

             // Store OTP in the database
             PasswordResetModel::create([
                 'user_id' => $user->id,
                 'otp' => $otp
             ]);
         
             // Redirect to OTP input page
            //  return view('auth.take-otp', ['user_id' => $user->id]);
             return redirect()->route('take.otp', ['user_id' => $user->id]);


         }

          // Verify OTP
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'otp' => 'required']);

            $record = PasswordResetModel::where('user_id', $request->user_id)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid or expired OTP');
        }

        return redirect()->route('reset.form', ['user_id' => $request->user_id]);
    }

// Show password reset form
    public function showResetForm($user_id)
{
    return view('auth.reset-form', ['user_id' => $user_id]);
}

// Show password reset form
    public function showOtpForm($user_id)
{
    return view('auth.take-otp', ['user_id' => $user_id]);

}

// update the password
         public function reset(Request $request)
        {
            $request->validate([
                'user_id' => '',
                'password' => 'required|confirmed'
            ]);

            User::where('id', $request->user_id)->update([
                'password' => Hash::make($request->password)
            ]);

            PasswordResetModel::where('user_id', $request->user_id)->delete();

            return redirect()->route('login')->with('success', 'Password reset successfully');
        }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
