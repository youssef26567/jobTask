<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;



class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // a. View all posts (only authenticated user's posts, with pinned posts first)
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts()->with('tags')->orderByDesc('pinned')->get();
        return response()->json($posts);
    }

    // b. Store a new post
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $path = $request->file('cover_image')->store('covers', 'public');

        $post = Auth::user()->posts()->create([
            'title' => $validatedData['title'],
            'body' => $validatedData['body'],
            'cover_image' => $path,
            'pinned' => $validatedData['pinned'],
        ]);

        $post->tags()->attach($validatedData['tags']);

        return response()->json($post, 201);
    }

    // c. View a single post
    public function show($id)
    {
        $post = Auth::user()->posts()->with('tags')->findOrFail($id);
        return response()->json($post);
    }

    // d. Update a post
    public function update(Request $request, $id)
    {
        $post = Auth::user()->posts()->findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $post->cover_image = $path;
        }

        $post->update([
            'title' => $validatedData['title'],
            'body' => $validatedData['body'],
            'pinned' => $validatedData['pinned'],
        ]);

        $post->tags()->sync($validatedData['tags']);

        return response()->json($post);
    }
    // e. Soft delete a single post
    public function destroy($id)
    {
        $post = Auth::user()->posts()->findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post soft deleted.']);
    }

    // f. View all soft deleted posts
    public function deletedPosts()
    {
        $posts = Auth::user()->posts()->onlyTrashed()->get();
        return response()->json($posts);
    }

    // g. Restore a soft deleted post
    public function restore($id)
    {
        $post = Auth::user()->posts()->onlyTrashed()->findOrFail($id);
        $post->restore();

        return response()->json(['message' => 'Post restored.']);
    }
}
