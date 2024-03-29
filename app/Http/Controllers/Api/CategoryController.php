<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Master\CategoryResource;
use App\Http\Resources\PostListResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\DataIklan;
use App\Models\Post;
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
            $ads = DataIklan::where('category_id', $value->id)->whereHas('jenisIklan', function ($query) {
                return $query->where('kode_jenis_iklan', 'ads-feed');
            })->inRandomOrder()->first();
            if ($value->layout_name == 'layout-1') {
                $newCategories[$key][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'banners' => $value->banners,
                    'layout_name' => $value->layout_name,
                    'posts' => [
                        'data' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->orderBy('likes_count', 'asc')->limit(5)->get()),
                        'dataTwo' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->orderBy('likes_count', 'asc')->limit(7)->get()),
                    ],

                ];
            }

            if ($value->layout_name == 'layout-2') {
                $newCategories[$key][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'banners' => $value->banners,
                    'layout_name' => $value->layout_name,
                    'posts' => [
                        'data' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(7)->orderBy('created_at', 'desc')->get()),
                        'ads' => $ads,
                    ]
                ];
            }

            if ($value->layout_name == 'layout-3') {
                $newCategories[$key][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'banners' => $value->banners,
                    'layout_name' => $value->layout_name,
                    'posts' => [
                        'data' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(7)->orderBy('created_at', 'desc')->get()),
                        'ads' => $ads,
                    ]
                ];
            }

            if ($value->layout_name == 'layout-4') {
                $newCategories[$key][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'banners' => $value->banners,
                    'layout_name' => $value->layout_name,
                    'posts' => [
                        'data' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(1)->orderBy('created_at', 'desc')->get()),
                        'dataTwo' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(7)->get()),
                        'dataThree' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(7)->get()),
                        'ads' => $ads,
                    ]

                ];
            }

            if ($value->layout_name == 'layout-5') {
                $newCategories[$key][] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'slug' => $value->slug,
                    'banners' => $value->banners,
                    'layout_name' => $value->layout_name,
                    'posts' => [
                        'data' => PostListResource::collection(Post::whereStatus('publish')->wherePublishStatus('published')->where('category_id', $value->id)->limit(7)->orderBy('created_at', 'desc')->get()),
                        'ads' => $ads,
                    ]
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => array_values($newCategories)
        ]);
    }
}
