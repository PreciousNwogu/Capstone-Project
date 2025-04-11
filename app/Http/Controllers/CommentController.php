<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{

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

    public function store(Request $request, Idea $idea)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No user found'], 404);
    }

    $comment = $idea->comments()->create([
        'content' => $validated['content'],
        'user_id' => $user->id,
    ]);

    return response()->json($comment, 201);
}

    public function show(string $id)
    {
        //
    }


   public function update(Request $request, Comment $comment)
{
    $validated = $request->validate([
        'content' => 'required|string',
    ]);

    $comment->update($validated);

    return response()->json($comment);
}

   public function destroy(Comment $comment)
{
    $comment->delete();

    return response()->json(['message' => 'Comment deleted successfully'], 200);
}

}
