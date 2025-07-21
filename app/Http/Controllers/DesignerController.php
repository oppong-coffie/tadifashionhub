<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PaidModel;
use App\Models\RejectedModel;
use App\Models\ServedModel;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Psr7\Utils;





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
        // Step 1: Validate incoming request
        $validatedData = $request->validate([
            'designer_id'    => 'required|string',
            'product_name'   => 'required|string',
            'product_image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_price'  => 'required|numeric',
        ]);
    
        // Step 2: Check and process image
        if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
            $file      = $request->file('product_image');
            $fileName  = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath  = $file->getPathname();
            $bucket    = 'materials'; // your Supabase bucket name
    
            // Step 3: Upload to Supabase Storage
            try {
                $response = Http::withOptions(['verify' => true]) // remove 'verify' => false if SSL is fixed
                    ->withHeaders([
                        'apikey'        => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJjeGpjZW5oa3J4c25hYmdheG93Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1MzA5OTY1MiwiZXhwIjoyMDY4Njc1NjUyfQ.AS47W9F5dEEVIAv12tZbAA00xMegIgTtVSeG7O-RcPI',
                        'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJjeGpjZW5oa3J4c25hYmdheG93Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1MzA5OTY1MiwiZXhwIjoyMDY4Njc1NjUyfQ.AS47W9F5dEEVIAv12tZbAA00xMegIgTtVSeG7O-RcPI',
                        'Content-Type'  => $file->getMimeType(),
                    ])->withBody(
                        Utils::streamFor(fopen($filePath, 'r')),
                        $file->getMimeType()
                    )->put("https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/$bucket/$fileName");
    
                if ($response->successful()) {
                    // Set public URL (make sure bucket policy allows public access)
                    $validatedData['product_image'] = "https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/public/$bucket/$fileName";
                } else {
                    return back()->with('error', 'Image upload failed: ' . $response->body());
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Upload error: ' . $e->getMessage());
            }
        }
    
        // Step 4: Save to DB
        Product::create($validatedData);
    
        return back()->with('success', 'Product has been added successfully!');
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
    
    // START:: Move product from the paid tableto the served table
    public function servedProducts(Request $request, $productid)
    {
        // Find the paid product by ID
        $paidProduct = PaidModel::findOrFail($productid);
    
        // Store the served product
        ServedModel::create([
            "designer_id"   => $paidProduct->designer_id,
            "customer_id"   => $paidProduct->customer_id,
            "customer_name" => $paidProduct->customer_name,
            "product_name"  => $paidProduct->product_name,
            "product_image" => $paidProduct->product_image,
            "product_price" => $paidProduct->product_price,
        ]);
    
        // Delete the paid product after serving it
        $paidProduct->delete();
    
        return back()->with('success', 'Product has been served successfully!');
    }
    // END:: Move product from the paid table to the served table
    
    // START:: Move product from the paid tableto the served table
    public function rejectProducts(Request $request, $productid)
    {
        // Find the paid product by ID
        $paidProduct = PaidModel::findOrFail($productid);
    
        // Store the served product
        RejectedModel::create([
            "designer_id"   => $paidProduct->designer_id,
            "customer_id"   => $paidProduct->customer_id,
            "customer_name" => $paidProduct->customer_name,
            "product_name"  => $paidProduct->product_name,
            "product_image" => $paidProduct->product_image,
            "product_price" => $paidProduct->product_price,
        ]);
    
        // Delete the paid product after serving it
        $paidProduct->delete();
    
        return back()->with('success', 'Product has been rejected successfully!');
    }
    // END:: Move product from the paid table to the served table


    // START::Deleting a product
    public function deleteProducts(Request $request, $id)
    {
        // Step 1: Get the product by ID
        $product = Product::where('product_id', $id)->first();
    
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }
    
        // Step 2: Extract file name from Supabase public URL
        $imageUrl = $product->product_image;
        $bucket = 'materials';
    
        // Parse the URL and get just the path portion
        $parsedPath = parse_url($imageUrl, PHP_URL_PATH); // eg. /storage/v1/object/public/materials/filename.png
    
        // This will extract just the file name portion after /public/materials/
        $needle = "/storage/v1/object/public/{$bucket}/";
        $fileName = ltrim(Str::after($parsedPath, $needle), '/');
    
        // Step 3: Send DELETE request to Supabase
        $deleteResponse = Http::withHeaders([
            'apikey' => env('SUPABASE_SERVICE_KEY'),
            'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
        ])->delete("https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/$bucket/$fileName");
    
        // Step 4: Check response
        if (!$deleteResponse->successful()) {
            return back()->with('error', 'Failed to delete image from Supabase: ' . $deleteResponse->body());
        }
    
        // Step 5: Delete the product from the database
        $product->delete();
    
        return back()->with('success', 'Product and its image deleted successfully.');
    }
    // END::Deleting a product

     // START::  Update Product
     public function updateProduct(Request $request)
     {
         // Validate input data
         $validatedData = $request->validate([
             'product_id' => 'required|exists:products,product_id',
             'product_name' => 'required|string|max:255',
             'product_price' => 'required|numeric',
             'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         ]);
     
         // Fetch product
         $product = Product::findOrFail($validatedData['product_id']);
         $product->product_name = $validatedData['product_name'];
         $product->product_price = $validatedData['product_price'];
     
         // If a new image is uploaded
         if ($request->hasFile('product_image') && $request->file('product_image')->isValid()) {
             $file = $request->file('product_image');
             $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
             $filePath = $file->getPathname();
             $bucket = 'materials';
     
             // DELETE old image from Supabase
             if ($product->product_image) {
                 $parsedPath = parse_url($product->product_image, PHP_URL_PATH);
                 $fileToDelete = Str::after($parsedPath, "/storage/v1/object/public/{$bucket}/");
     
                 Http::withHeaders([
                     'apikey' => env('SUPABASE_SERVICE_KEY'),
                     'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                 ])->delete("https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/$bucket/$fileToDelete");
             }
     
             // UPLOAD new image to Supabase
             $uploadResponse = Http::withHeaders([
                 'apikey' => env('SUPABASE_SERVICE_KEY'),
                 'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                 'Content-Type' => $file->getMimeType(),
             ])->withBody(
                 \GuzzleHttp\Psr7\Utils::streamFor(fopen($filePath, 'r')),
                 $file->getMimeType()
             )->put("https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/$bucket/$fileName");
     
             if ($uploadResponse->successful()) {
                 $product->product_image = "https://bcxjcenhkrxsnabgaxow.supabase.co/storage/v1/object/public/$bucket/$fileName";
             } else {
                 return back()->with('error', 'Image upload failed: ' . $uploadResponse->body());
             }
         }
     
         // Save updated product
         $product->save();
     
         return back()->with('success', 'Product updated successfully!');
     }
        // END::  Update Product



}
