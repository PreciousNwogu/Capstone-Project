<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IdeaController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $ideas = Idea::with(['user', 'comments', 'upvotes'])->get();
    return response()->json($ideas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Temporarily get the first user in the database
    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }
    // Create the idea associated with the fetched user
    $idea = $user->ideas()->create($validated);

    return response()->json($idea, 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
{
    $idea->load(['user', 'comments', 'upvotes']);
    return response()->json($idea, 200);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Idea $idea)
{
    // $this->authorize('update', $idea);

    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
    ]);

    $idea->update($validated);

    return response()->json($idea, 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(idea $idea)
    {
    //      $this->authorize('delete', $idea);
    // $idea->delete();
    $idea->delete();


    return response()->json(['message' => 'Idea deleted successfully'], 200);
    }
}
