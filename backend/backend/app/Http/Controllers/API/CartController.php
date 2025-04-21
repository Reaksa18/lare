<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ✅ Private helper method to get cart count
    private function getCartCountValue($userId)
    {
        return DB::table('cart')
            ->where('user_id', $userId)
            ->count();
    }

    // ✅ Get all items in the user's cart, including the cart count
    public function getCartItems(Request $request)
    {
        $userId = auth()->id();  // Get the authenticated user ID

        // Fetch the cart items
        $cartItems = DB::table('cart')
            ->where('user_id', $userId)
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'cart.quantity',
                DB::raw("CONCAT('http://127.0.0.1:8000/storage/', products.image) as image")  // Full image URL
            )
            ->get();

        // Get the cart count directly
        $cartCount = $this->getCartCountValue($userId);

        // Return both cart items and cart count
        return response()->json([
            'cart_items' => $cartItems,
            'cart_count' => $cartCount
        ]);
    }

    // ✅ Add a product to the cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = auth()->id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $existing = DB::table('cart')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            DB::table('cart')
                ->where('id', $existing->id)
                ->increment('quantity', $quantity);
        } else {
            DB::table('cart')->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Product added to cart']);
    }

    // ✅ Remove a product from the cart
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = auth()->id();

        DB::table('cart')
            ->where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }

    // ✅ Update quantity of an item in the cart
    public function updateCart(Request $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Update Cart Request:', $request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id', // Ensure product exists
            'action' => 'required|in:increase,decrease', // Ensure action is either increase or decrease
        ]);

        $userId = auth()->id();

        // Fetch the cart item
        $cartItem = DB::table('cart')
            ->where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // Update the cart based on the action
        if ($request->action === 'increase') {
            DB::table('cart')
                ->where('id', $cartItem->id)
                ->increment('quantity', 1);
        } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
            DB::table('cart')
                ->where('id', $cartItem->id)
                ->decrement('quantity', 1);
        } else {
            return response()->json(['message' => 'Invalid action or quantity'], 422);
        }

        return response()->json(['message' => 'Cart updated']);
    }

    // ✅ Public route version: Get cart count only
    public function getCartCount(Request $request)
    {
        $userId = auth()->id();
        $cartCount = $this->getCartCountValue($userId);

        return response()->json(['count' => $cartCount]);
    }
}
