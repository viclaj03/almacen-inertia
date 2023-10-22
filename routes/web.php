<?php

use App\Http\Controllers\ImagePostController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoPostController;
use App\Models\ImagePost;
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

//dd("la pagina no va \n favoritos ha fallado, probar con migracion y ayuda");



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    //'auth:sanctum',
    //config('jetstream.auth_session'),
    //'verified',
])->group(function () {
    Route::get('/dashboard', function () {

        $user = Auth::user();
        if ($user) {
            $images = ImagePost::where(function($query) use ($user) {
                $query->where(function($subQuery) use ($user) {
                    $subQuery->where('private', 0)
                             ->orWhere('user_post', $user->id);
                });
                
                if (!$user->pegi_18) {
                    $query->where('pegi_18', false);
                }
            });
        } else {
            $images = ImagePost::where('private', 0)
                               ->where('pegi_18', false);
        }
        $images = $images->inRandomOrder()->limit(20)->get();
        $numImages = ImagePost::count();
        $numImages18 = ImagePost::where('pegi_18', '!=', true)->count();
        $imagesFaltan = ImagePost::wherehas('tags', function($q){
            $q->where('tag_id',9);
        })->count();
        
        return Inertia::render('Dashboard',compact('images','numImages','numImages18','imagesFaltan'));
    })->name('dashboard');//->middleware(['verified']) ;
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

Route::post('/add-fovorite/{id}',[ImagePostController::class,'addFavorite'])->name('image.addFavorite');

Route::get('/my-favorite',[ImagePostController::class,'seeFavorite'])->name('image.favorite');

Route::any('/search',[ImagePostController::class,'search'])->name('image.search');

Route::get('/UniqHash/{imagenHash}',[ImagePostController::class,'searchByUniqHash'])->name('images.uniqHash');


Route::resource('/videos',VideoPostController::class)->middleware('auth');


Route::resource('/tags',TagController::class)->middleware('auth');




