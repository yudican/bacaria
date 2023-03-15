<?php

use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    // get post list
    Route::post('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);
    Route::get('posts/tag/{slug}', [PostController::class, 'tag']);
    Route::get('posts/author/{slug}', [PostController::class, 'author']);
    Route::post('posts/search', [PostController::class, 'search']);

    // get category list
    Route::post('category', [CategoryController::class, 'index']);
    Route::get('category/{slug}', [CategoryController::class, 'show']);
    Route::get('category/post/{slug}', [CategoryController::class, 'post']);

    // get ads list
    Route::post('ads', [AdsController::class, 'index']);
});
