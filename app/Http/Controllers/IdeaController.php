<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IdeaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $ideas = Idea::with(['user', 'comments', 'upvotes'])->get();
    return response()->json($ideas, 200);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }
    $idea = $user->ideas()->create($validated);

    return response()->json($idea, 201);
}

    public function show(Idea $idea)
{
    $idea->load(['user', 'comments', 'upvotes']);
    return response()->json($idea, 200);
}

    public function update(Request $request, Idea $idea)
{

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
    ]);

    $idea->update($validated);

    return response()->json($idea, 200);
}


    public function destroy(idea $idea)
    {
    $idea->delete();


    return response()->json(['message' => 'Idea deleted successfully'], 200);
    }
}
