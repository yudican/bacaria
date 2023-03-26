<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Master\AdsResource;
use App\Models\DataIklan;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    // get ads list
    public function index()
    {
        $ads = DataIklan::all();
        $ad = AdsResource::collection($ads);
        return response()->json([
            'status' => 'success',
            'data' => $ad
        ]);
    }
}
