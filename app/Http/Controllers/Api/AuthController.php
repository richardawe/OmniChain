<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * User login
     */
    public function login(Request $request): JsonResponse
    {
        // Placeholder implementation
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => 'sample_token',
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ]
            ]
        ]);
    }

    /**
     * User registration
     */
    public function register(Request $request): JsonResponse
    {
        // Placeholder implementation
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'token' => 'sample_token',
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ]
            ]
        ]);
    }

    /**
     * User logout
     */
    public function logout(Request $request): JsonResponse
    {
        // Placeholder implementation
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}