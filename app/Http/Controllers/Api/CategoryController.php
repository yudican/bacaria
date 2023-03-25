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

    public function postLists()
    {
        $newCategories = [];
        $categories = Category::with(['banners'])->get();

        foreach ($categories as $key => $value) {
            switch ($value->layout_name) {
                case 'layout-1':
                    $newCategories[$key]['posts'][] = [
                        'data' => $value->posts()->limit(5)->orderBy('created_at', 'desc')->get(),
                        'dataTwo' => $value->posts()->limit(7)->get(),
                    ];
                case 'layout-2':
                    $newCategories[$key]['posts'][] = $value->posts()->limit(7)->orderBy('created_at', 'desc')->get();
                case 'layout-3':
                    $newCategories[$key]['posts'][] = $value->posts()->limit(5)->orderBy('created_at', 'desc')->get();
                case 'layout-4':
                    $newCategories[$key]['posts'][] = [
                        'data' => $value->posts()->limit(1)->orderBy('created_at', 'desc')->get(),
                        'dataTwo' => $value->posts()->limit(7)->get(),
                        'dataThree' => $value->posts()->limit(7)->get(),
                    ];
                case 'layout-5':
                    $newCategories[$key]['posts'][] = $value->posts()->limit(5)->orderBy('created_at', 'desc')->get();
                default:
                    $newCategories[$key]['posts'][] = $value->posts()->limit(5)->orderBy('created_at', 'desc')->get();
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $newCategories
        ]);
    }
}
