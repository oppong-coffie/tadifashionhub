<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\PaidModel;
use App\Models\CartModel;
use App\Models\RejectedModel;
use App\Models\ServedModel;
use App\Models\User;
use Illuminate\Support\Facades\Http;


class customerController extends Controller
{
    //
    public function getallproducts()
    {
        // Demo Data for Products
        $products = [
            [
                'name' => 'Floral Summer Dress',
                'price' => 49.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Floral+Summer+Dress'
            ],
            [
                'name' => 'Casual Denim Jacket',
                'price' => 79.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Casual+Denim+Jacket'
            ],
            [
                'name' => 'Classic Leather Boots',
                'price' => 129.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Classic+Leather+Boots'
            ],
            [
                'name' => 'Stylish Sunglasses',
                'price' => 19.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Stylish+Sunglasses'
            ],
            [
                'name' => 'Elegant Handbag',
                'price' => 59.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Elegant+Handbag'
            ],
            [
                'name' => 'Running Sneakers',
                'price' => 89.99,
                'image' => 'https://via.placeholder.com/300x300.png?text=Running+Sneakers'
            ],
        ];

        return view('fashion_shop', compact('products'));
    }

    // Login User
    public function customerDashboard(Request $request)
    {
        // Get the user ID from session
        $userId = $request->session()->get('user_id');
    
        if (!$userId) {
            return redirect()->route('login')->withErrors([
                'email' => 'You must be logged in to access this page.',
            ]);
        }
    
        // Load user from database
        $user = User::find($userId);
    
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid session, please log in again.',
            ]);
        }
    
        $products         = Product::all();
        $paidproducts     = PaidModel::where('customer_id', $user->id)->get();
        $servedproducts   = ServedModel::where('customer_id', $user->id)->get();
        $rejectedproducts = RejectedModel::where('customer_id', $user->id)->get();
        $cartItems        = CartModel::where('customer_id', $user->id)->get();
    
        return view('customer.dashboard', [
            'user_name'        => $user->name,
            'profile_image'    => $user->profile_image ?? 'default.jpg',
            'products'         => $products,
            'paidproducts'     => $paidproducts,
            'servedproducts'   => $servedproducts,
            'rejectedproducts' => $rejectedproducts,
            'cartItems'        => $cartItems,
        ]);
    }
    


    // START:: Add to cart
    public function addToCart(Request $request)
    {
        // Validate Request
        $request->validate([
            'product_id' => 'required',
            'designer_id' => '',
            'product_name' => 'required',
            'product_image' => 'required',
            'product_price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        // Calculate subtotal
        $subtotal = $request->product_price * $request->quantity;

        // Store in Cart Table
        CartModel::create([
            'customer_id' => auth()->id(), // If user is logged in
            'product_id' => $request->product_id,
            'designer_id' => $request->designer_id,
            'product_name' => $request->product_name,
            'product_image' => $request->product_image,
            'product_price' => $request->product_price,
            'quantity' => $request->quantity,
            'subtotal' => $subtotal,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
  
      // Remove item from cart
      public function removeFromCart($id)
      {
          CartMODEL::where('id', $id)->delete();
          return response()->json(['message' => 'Item removed from cart']);
      }

// Get detail page 
public function detailToCart($designer_id, $product_image, $product_price, $product_name)
{
    $detailItems = [
       'designer_id' => $designer_id,
        'product_image' => rawurldecode($product_image),  // Decode URL-encoded data
        'product_price' => $product_price,
        'product_name' => rawurldecode($product_name),
    ];

    return view('customer.detail', compact('detailItems'));
}

// Initiate the Paystack payment
public function initiatePayment(Request $request)
{
    $userId = Auth::id();
    $cartItems = CartModel::where('customer_id', $userId)->get();

    if ($cartItems->isEmpty()) {
        return back()->with('error', 'Your cart is empty.');
    }

    // Calculate total
    $totalAmount = $cartItems->sum(function ($item) {
        return $item->product_price * $item->quantity;
    });

    // Prepare data for Paystack
    $paymentData = [
        'email' => Auth::user()->email,
        'amount' => $totalAmount * 100,  // Paystack needs amount in kobo
        'callback_url' => route('payment.callback'),
    ];

    // Initialize Paystack transaction
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        'Accept'        => 'application/json',
    ])->post('https://api.paystack.co/transaction/initialize', $paymentData);

    $data = $response->json();

    if (isset($data['status']) && $data['status'] === true) {
        return redirect($data['data']['authorization_url']);
    }

    return back()->with('error', 'Payment initialization failed. Please try again.');
}


// Handle Paystack callback (after successful payment)
public function paymentCallback(Request $request)
{
    $userId = Auth::id();

    // Paystack returns reference in the query string
    $reference = $request->get('reference');
    if (!$reference) {
        return redirect()->route('customer.dashboard')->with('error', 'No payment reference found.');
    }

    // Verify payment with Paystack
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
    ])->get('https://api.paystack.co/transaction/verify/' . $reference);

    $data = $response->json();

    if (isset($data['status']) && $data['status'] === true && $data['data']['status'] === 'success') {
        // ✅ Payment successful
        $cartItems = CartModel::where('customer_id', $userId)->get();

        foreach ($cartItems as $item) {
            PaidModel::create([
                'designer_id'   => $item->designer_id,
                'customer_id'   => $userId,
                'customer_name' => Auth::user()->name,
                'product_name'  => $item->product_name,
                'product_image' => $item->product_image,
                'product_price' => $item->product_price,
                'quantity'      => $item->quantity,
                'reference'     => $reference, // 🔑 Keep Paystack ref for tracking
            ]);
        }

        // Clear cart
        CartModel::where('customer_id', $userId)->delete();

        return redirect()->route('customer.dashboard')
            ->with('success', 'Payment successful! Your items are now in purchases.');
    }

    // ❌ Payment failed
    return redirect()->route('customer.dashboard')->with('error', 'Payment verification failed. Please try again.');
}

}
