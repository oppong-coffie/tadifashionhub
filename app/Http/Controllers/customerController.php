<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\PaidModel;
use App\Models\RejectedModel;
use App\Models\ServedMOdel;
use App\Models\User;

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
    public function customerDashboard()
    {
        $user = Auth::user(); // Get the authenticated user
    
        if ($user->role !== 'customer') {
            return redirect()->route('login')->withErrors(['access' => 'Unauthorized access.']);
        }
    
        $products = Product::get();
        $paidproducts = PaidModel::where('customer_id', $user->id)->get();
        $servedproducts = servedModel::where('customer_id', $user->id)->get();
        $rejectedproducts = rejectedModel::where('customer_id', $user->id)->get();
    
        return view('customer.dashboard', [
            'user_name' => $user->name,
            'profile_image' => $user->profile_image ?? 'default.jpg',
            'products' => $products,
            'paidproducts' => $paidproducts,
            'servedproducts' => $servedproducts,
            'rejectedproducts' => $rejectedproducts,
        ]);
    }
}
