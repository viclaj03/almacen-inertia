<?php

use App\Http\Controllers\ImagePostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoPostController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboardhh', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

/*
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/users', function () {

        $users = User::paginate(0);

        return Inertia::render('User',compact('users',));
    })->name('user.index');
});

*/








Route::post('/change-pegui', [UserController::class,'changePegui'])->name('user.pegi');

Route::resource('/user',UserController::class)->middleware('auth');

Route::resource('/images',ImagePostController::class);
Route::get('/upload-image-by-url',[ImagePostController::class,'uploadByUrl'])->name('image.url');


Route::resource('/videos',VideoPostController::class)->middleware('auth');