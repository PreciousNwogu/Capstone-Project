<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:1000',
        'avatar' => 'nullable|url',
    ]);

    $user->update($request->only(['name', 'bio', 'avatar']));

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user
    ]);
}

   public function show(User $user)
{
    return response()->json([
        'user' => $user
    ]);
}
}
