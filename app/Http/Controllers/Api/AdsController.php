<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Master\AdsResource;
use App\Models\DataIklan;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    // get ads list
    public function index(Request $request)
    {
        $perPage = $request->query('page', 10);

        $ads = DataIklan::paginate($perPage);
        $ad = AdsResource::collection($ads)->additional([
            'meta' => [
                'total' => $ads->total(),
                'perPage' => $ads->perPage(),
                'currentPage' => $ads->currentPage(),
                'lastPage' => $ads->lastPage(),
            ],
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $ad
        ]);
    }
}
