<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\Tag;
use App\Models\User;
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
            'data' => PostResource::collection($posts)
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
        $perPage = $request->page;
        $search = $request->search;

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
    public function like(Request $request, $post_id)
    {
        $user = User::find($request->user_id);
        $post = Post::where('uid_post', $post_id)->first();
        // unlike post
        $postLike = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();
        if ($postLike) {
            $postLike->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Post unliked successfully'
            ]);
        }

        PostLike::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'referer' => request()->header('referer')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post liked successfully'
        ]);
    }

    // post comment
    public function comment(Request $request, $post_id)
    {
        $user = auth()->user();

        $post = Post::where('uid_post', $post_id)->first();

        PostComment::create([
            'post_id' => $post->id,
            'user_id' => $user ? $user->id : $request->user_id,
            'parent_id' => $request->parent_id,
            'comment' => $request->comment,
            'status' => 'approved',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted successfully'
        ]);
    }
}
