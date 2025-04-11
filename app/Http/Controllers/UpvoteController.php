<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;
use Illuminate\Support\Facades\Response;



class UpvoteController extends Controller
{

    public function toggle(Idea $idea)
    {
        $user = \App\Models\User::first();

        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }

        $upvote = $idea->upvotes()->where('user_id', $user->id)->first();

        if ($upvote) {
            $upvote->delete();
            return response()->json(['message' => 'Upvote removed'], 200);
        }

        $idea->upvotes()->create(['user_id' => $user->id]);
        return response()->json(['message' => 'Upvoted successfully'], 201);
    }

    public function index(Idea $idea)
    {
        $upvotesCount = $idea->upvotes()->count();
        return response()->json(['upvotes' => $upvotesCount], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
