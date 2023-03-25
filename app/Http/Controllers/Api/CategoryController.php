<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Master\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // get category list
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    // get category by slug
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();
        return response()->json([
            'status' => 'success',
            'data' => new CategoryResource($category)
        ]);
    }

    // get post by category
    public function post($slug)
    {
        $category = Category::where('slug', $slug)->first();
        $posts = $category->posts()->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function posts()
    {
        $categories = Category::with(['posts'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
