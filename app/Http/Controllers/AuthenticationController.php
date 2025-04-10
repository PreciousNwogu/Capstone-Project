<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Instead of $request->all(), use $request->only([...])
            $validator = Validator::make($request->only('name', 'email', 'password', 'password_confirmation'), [
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            // Create user with request data
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Account created successfully.',
                'user' => $user,
                'redirect_url' => url('/') // Add redirect URL to login page
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function login(Request $request)
{
    // Use only() instead of all() or assuming JSON
    $fields = $request->only('email', 'password');

    $validator = Validator::make($fields, [
        'email' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::where('email', $fields['email'])->first();

    if (!$user || !Hash::check($fields['password'], $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('apitoken')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token,
        'redirect_url' => url('/') // Add the redirect URL here
    ], 200);
}


    // public function logout(Request $request)
    // {
    //     if (!$request->user()) {
    //         return response()->json([
    //             'message' => 'Unauthorized. No user authenticated.'
    //         ], 401);
    //     }

    //     $request->user()->tokens()->delete();

    //     return response()->json([
    //         'message' => 'Logged out successfully.'
    //     ], 200);
    // }
}
