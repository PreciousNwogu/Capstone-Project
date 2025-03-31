<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function all()
{
    $comments = \App\Models\Comment::with(['idea', 'user'])->get();

    return response()->json($comments, 200);
}


    public function index(Idea $idea)
    {

         $comments = $idea->comments()->with('user')->get();

         return response()->json($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Idea $idea)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    // Temporarily fetch first user from the database
    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }

    $comment = $idea->comments()->create([
        'content' => $validated['content'],
        'user_id' => $user->id,  // Associate the comment with the fetched user
    ]);

    return response()->json($comment, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Comment $comment)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    $comment->update($validated);

    return response()->json($comment);
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Comment $comment)
{
    $comment->delete();

    return response()->json(['message' => 'Comment deleted successfully'], 200);
}

}
