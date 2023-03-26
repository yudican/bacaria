<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // get post list
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 10;

        $posts = Post::whereStatus('publish')->wherePublishStatus('published')->paginate($perPage);
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    // get post by slug
    public function show($slug)
    {
        $post = Post::with(['comments', 'tags'])->whereStatus('publish')->wherePublishStatus('published')->where('slug', $slug)->first();
        return response()->json([
            'status' => 'success',
            'data' => new PostResource($post)
        ]);
    }

    // get post by tag
    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        $posts = $tag->posts()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    // get post by author
    public function author($author_id)
    {
        $posts = Post::where('user_id', $author_id)->whereStatus('publish')->wherePublishStatus('published')->paginate(10);
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

        $posts = Post::query()->whereStatus('publish')->wherePublishStatus('published');
        $posts->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%');
        })->paginate($perPage);
        return response()->json([
            'status' => 'success',
            'data' => PostResource::collection($posts)
        ]);
    }

    // like post
    public function like($post_id)
    {
        $user = auth()->user();

        // unlike post
        if ($user->postLikes()->where('post_id', $post_id)->exists()) {
            $user->postLikes()->where('post_id', $post_id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Post unliked successfully'
            ]);
        }


        PostLike::create([
            'post_id' => $post_id,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'referer' => request()->header('referer')
        ]);
    }

    // post comment
    public function comment(Request $request, $post_id)
    {
        $user = auth()->user();

        PostComment::create([
            'post_id' => $post_id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted successfully'
        ]);
    }
}
