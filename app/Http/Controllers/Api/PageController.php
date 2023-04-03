<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataHalaman;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function list()
    {
        $pages = DataHalaman::all();

        return response()->json([
            'status' => 'success',
            'data' => $pages
        ]);
    }
}
