<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\ImagePost;
use Carbon\Carbon;
use Hamcrest\Type\IsBoolean;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $name = request()->search ?? '';
        
        $favoritos = request()->favoritos ;


        
       

        
        $artists = Artist::with('tag')->where('name','like','%' . $name . '%');


        //dd(request()->favoritos);
        if ($favoritos == 'true' ) {
            //dd(request()->favoritos);
            //dd(99);
            //dd(gettype($favoritos));
            $artists = $artists->whereHas('favoritedBy',function ($q)  {
                $q->where('user_id', Auth::id());
            });
        }
        
        
        $artists = $artists->paginate(13)->withQueryString();
       
       foreach ($artists as $artist) {
        $artist->isFavorite = $user->favoriteArtists->contains($artist->id);
       }

       
        
        return Inertia::render('Artist/ArtistsList', compact('artists'));
    }

    /**
     * Show the form for creating a new resource. 
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $artist = Artist::with('tag','urls_old')->findOrFail($id);

        $comment = $user->getCommentForArtist($id);

        $artist->isFavorite = $user->favoriteArtists->contains($artist->id);

        
        $images = ImagePost::wherehas('tags', function ($q) use ($artist) {
            $q->where('tag_id',$artist->tag->id);
        })->limit(10)->get();

        

        foreach ($images as $image) {
            $image->isFavorited = $user->favoriteImages->contains($image->id);
        }

        return Inertia::render('Artist/ArtistShow', compact('artist','images','comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function addFavorite(Request $request){

        $image = Artist::findOrFail($request->id);

        if($image->isFavoritedByUser()){
            //dd($image->favoritedBy->where('user_id',Auth::user()->id));
            $image->favoritedBy()->detach(Auth::user()->id);
        } else {
            $image->favoritedBy()->attach(Auth::user()->id,['comment'=>"revisar","last_change_date"=>Carbon::now()]);
        }
        
    }


}
