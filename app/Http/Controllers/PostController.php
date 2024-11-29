<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    //
    public function posts(Request $request)
    {
        // Initialize the query to get all posts
        $query = Post::query();

        // Apply search filter for title or content
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }
        return response()->json($post);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        // Create a new post using the authenticated user
        $post = Post::create([
            'title' => $validated['title'],
            'body'  => $validated['body'],
            'user_id' => Auth::id(),  //
        ]);


        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        // Find the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to update this post.'], 403);
        }

        // Update the post
        $post->update([
            'title' => $validated['title'],
            'content'  => $validated['content'],
        ]);

        // Return the updated post with a success response
        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $post
        ]);
    }
    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found']);
        }

        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to delete this post.']);
        }

        // Delete the post
        $post->delete();

        // Return a success response
        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
