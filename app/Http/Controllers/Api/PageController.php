<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Http\Resources\PageResourceDetail;
use App\Models\DataHalaman;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function list()
    {
        $pages = DataHalaman::all();

        return response()->json([
            'status' => 'success',
            'data' => PageResource::collection($pages)
        ]);
    }

    public function show($slug)
    {
        $page = DataHalaman::where('slug', $slug)->first();

        return response()->json([
            'status' => 'success',
            'data' => new PageResourceDetail($page)
        ]);
    }
}
