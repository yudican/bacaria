<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // get 3 tags with most posts count
    public function popularTags()
    {
        $tags = Tag::orderBy('posts_count', 'desc')->limit(3)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tags
        ]);
    }

    public function topTags()
    {
        $tags = Tag::orderBy('posts_count', 'desc')->limit(10)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tags
        ]);
    }
}
