<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Middleware\QueryString;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function index($post_id)
    {
        // Find the post by ID
        $post = Post::find($post_id);

        // Check if the post exists
        if (!$post) {
            return response()->json(['error' => 'Post not found']);
        }

        // Fetch all comments associated with the post
        $comments = $post->comments;  // Assuming the Post model has a 'comments' relationship

        // Return the comments with a success response
        return response()->json([
            'comments' => $comments
        ], 200);
    }

    public function store(Request $request, $post_id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        // Find the post by ID
        $post = Post::find($post_id);

        // Check if the post exists
        if (!$post) {
            return response()->json(['error' => 'Post not found']);
        }

        // Create a new comment associated with the post
        $comment = Comment::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(), // Automatically associate the comment with the authenticated user
            'post_id' => $post->id,  // Link the comment to the post
        ]);

        // Return the created comment with a success message
        return response()->json([
            'message' => 'Comment added successfully.',
            'comment' => $comment
        ]);
    }

}
