<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Make sure this is added

class LoginController extends Controller
{
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

    // Create token
    $token = $user->createToken('apitoken')->plainTextToken;
    
    // Login the user for session-based auth as well
    Auth::login($user, $request->has('remember'));

    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'user' => $user,
        'token' => $token,
        'redirect_url' => url('/') // Redirect to homepage
    ], 200);
}
}