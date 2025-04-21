<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    // Register Admin
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json($admin, 201);
    }

    // Admin Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // Get Logged-in Admin Data
    public function me()
    {
        return response()->json(auth('admin')->user());
    }

    // Admin Logout
    public function logout(Request $request)
    {
        try {
            // Invalidate the current JWT token
            auth('admin')->logout();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }

    // Refresh the JWT Token
    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    // Respond with the Token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60 // Expiry time in seconds
        ]);
    }
}