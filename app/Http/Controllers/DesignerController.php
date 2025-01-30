<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaidModel;
use App\Models\RejectedModel;
use App\Models\ServedMOdel;
use App\Models\User;


class DesignerController extends Controller
{
    //Main dashboard
    public function designer_dashboard()
    {
        return view('designer.dashboard');
    }

    // START:: Add new product
    public function sendProduct(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'designer_id' => 'string',
            'product_name' => 'string',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file
            'product_price' => 'numeric', // Assuming product price is numeric
        ]);
    
        // Handle the file upload
        if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
            // Store the image file in the 'public/products' directory
            $imagePath = $request->file('product_image')->store('products', 'public');
    
            // Include the image path in the validated data to be saved in the database
            $validatedData['product_image'] = $imagePath;
        }
    
        // Create the product record in the database
        product::create($validatedData);
    
        // Return a success response
        return response()->json([
            'message' => 'Product created successfully!'
        ], 201); // 201 is HTTP status code for "Created"
    }
    // END:: Add new product


    public function getProducts($user_id)
    {
        // Retrieve the products associated with the designer
        $products = Product::where('designer_id', $user_id)->get();
        
        // Count the number of products
        $productCount = $products->count();  // Use the actual count of products
    
        // Retrieve the user data
        $user = User::find($user_id); // Use find() instead of where() for a single result
    
        // Get user name and profile image (with default fallback)
        $user_name = $user->name;  // Corrected syntax
        $profile_image = $user->profile_image ?? 'default.jpg';  // Corrected syntax and assignment operator
    
        // Pass data to the view
        return view('designer.products', compact('products', 'user_id', 'productCount', 'user_name', 'profile_image'));
    }
    



    public function designerDashboard()
    {
        $user = Auth::user(); // Get the authenticated user
    
        if ($user->role !== 'designer') {
            return redirect()->route('login')->withErrors(['access' => 'Unauthorized access.']);
        }
    
        $productCount = Product::where('designer_id', $user->id)->count();
        $products = Product::where('designer_id', $user->id)->get();
        $paidproducts = PaidModel::where('designer_id', $user->id)->get();
        $servedproducts = servedModel::where('designer_id', $user->id)->get();
        $rejectedproducts = rejectedModel::where('designer_id', $user->id)->get();
    
        return view('designer.dashboard', [
            'user_name' => $user->name,
            'profile_image' => $user->profile_image ?? 'default.jpg',
            'products' => $products,
            'paidproducts' => $paidproducts,
            'servedproducts' => $servedproducts,
            'rejectedproducts' => $rejectedproducts,
            'productCount' => $productCount,
        ]);
    }
    
    // Served product
    public function servedProducts(Request $request, $id, $designer_id, $customer_id, $customer_name, $product_name, $product_image, $product_price)
    {
        // Store the served product
        ServedMOdel::create([
            'designer_id' => $designer_id,
            'customer_id' => $customer_id,
            'customer_name' => $customer_name,
            'product_name' => $product_name,
            'product_image' => $product_image,
            'product_price' => $product_price
        ]);

        // Delete it from the paid table
        PaidModel::where('id', $id)->delete();
    
           // Redirect back with a success message
    return back()->with('success', 'Product has been served successfully!');
    }

    // START::Deleting a product
    public function deleteProducts(Request $request, $id)
    {
        // Delete the product with the given ID
        Product::where('product_id', $id)->delete();
    
        // Redirect back with a success message
        return back()->with('success', 'Product has been deleted successfully!');
    }   
    // END::Deleting a product

     // START::  Update Product
   public function updateProduct(Request $request)
{
    // Validate input data
    $validatedData = $request->validate([
        'product_id' => 'required|exists:products,product_id', // Ensure product exists
        'product_name' => 'required|string|max:255',
        'product_price' => 'required|numeric',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image validation
    ]);

    // Find the product by product_id
    $product = Product::findOrFail($validatedData['product_id']);
    $product->product_name = $validatedData['product_name'];
    $product->product_price = $validatedData['product_price'];

    // Handle the file upload for product_image
    if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
        $imagePath = $request->file('product_image')->store('products', 'public');
        $product->product_image = $imagePath; // Update image path in the database
    }

    // Save the updated product
    $product->save();

    return back()->with('success', 'Product updated successfully!');
}

     

        // START::  Update Product



}
