<?php

use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\AuthController;
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
    // auth
    Route::post('auth/login', [AuthController::class, 'loginApi']);
    Route::post('auth/register', [AuthController::class, 'register']);
    // get post list
    Route::post('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);
    Route::get('posts/tag/{slug}', [PostController::class, 'tag']);
    Route::get('posts/author/{author_id}', [PostController::class, 'author']);
    Route::post('posts/search', [PostController::class, 'search']);

    // route with middleware
    Route::post('posts/like/{post_id}', [PostController::class, 'like']);
    Route::post('posts/comment/{post_id}', [PostController::class, 'comment']);

    // get category list
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('category/{slug}', [CategoryController::class, 'show']);
    Route::get('category/all/posts', [CategoryController::class, 'postLists']);
    Route::get('category/post/{slug}', [CategoryController::class, 'post']);

    // get ads list
    Route::get('ads', [AdsController::class, 'index']);
    Route::get('tag/popular', [TagController::class, 'popularTags']);
    Route::get('tag/topten', [TagController::class, 'topTags']);

    // get page list
    Route::get('page/list', [PageController::class, 'list']);
});
