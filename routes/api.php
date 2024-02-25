<?php

use App\Http\Controllers\Api\ImagePostController;
use App\Http\Controllers\Api\TagController;
use App\Models\ImagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::apiResource('tags',TagController::class);

Route::apiResource('images',ImagePostController::class);

Route::get('searchby',[ImagePostController::class,'searchByUrl'])->name('Searchbyurl');


