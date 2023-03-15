<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // get post list
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 10;

        $posts = Post::paginate($perPage);
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    // get post by slug
    public function show($slug)
    {
        $post = Post::with(['comments', 'postTags'])->where('slug', $slug)->first();
        return response()->json([
            'status' => 'success',
            'data' => new PostResource($post)
        ]);
    }

    // get post by tag
    public function tag($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $posts = $post->postTags()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => PostResource::collection($posts)
        ]);
    }

    // get post by author
    public function author($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $posts = $post->author()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    // get post by search
    public function search(Request $request)
    {
        $perPage = $request->query('page', 10);
        $search = $request->query('search');

        $posts = Post::query();
        $posts->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return response()->json([
            'status' => 'success',
            'data' => PostResource::collection($posts)
        ]);
    }
}
