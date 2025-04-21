<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Fetch all products along with their categories
    public function index() {
        $products = Product::with('category')->get();

        // Format image URLs if they exist
        foreach ($products as $product) {
            if ($product->image) {
                $product->image = asset('storage/' . $product->image);
            }
        }

        return response()->json($products);
    }

    // Store a new product
    public function store(Request $request) {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // Image validation
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload if a file is provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create the new product
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Format the image URL if it was uploaded
        if ($product->image) {
            $product->image = asset('storage/' . $product->image);
        }

        return response()->json($product, 201); // Return created product with a 201 status
    }

    // Show a specific product by ID
    public function show($id) {
        $product = Product::with('category')->findOrFail($id);

        // Format image URL if it exists
        if ($product->image) {
            $product->image = asset('storage/' . $product->image);
        }

        return response()->json($product);
    }

    // Update an existing product by ID
    public function update(Request $request, $id) {
        // Find the product to update
        $product = Product::findOrFail($id);

        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048', // Image validation
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle image upload if a file is provided
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // Update product data
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        // Format the image URL if it was uploaded or updated
        if ($product->image) {
            $product->image = asset('storage/' . $product->image);
        }

        return response()->json($product); // Return updated product
    }

    // Delete a product by ID
    public function destroy($id) {
        $product = Product::findOrFail($id);

        // Delete the product's image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product record
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
 // ✅ Get products by category ID
 public function productsByCategory($categoryId) {
    $products = Product::where('category_id', $categoryId)->with('category')->get();

    foreach ($products as $product) {
        if ($product->image) {
            $product->image = asset('storage/' . $product->image);
        }
    }

    return response()->json($products);
}
}
