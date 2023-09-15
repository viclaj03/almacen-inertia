<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Artist;
use App\Models\ArtistUrl;
use App\Models\ImagePost;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{

    public function __construct()
    {
         $this->middleware('auth')->except('index','show');

         $this->middleware(function ($request, $next) {
            // Verifica si el usuario está autenticado y su ID es igual a 1
            if (!auth()->check() || auth()->user()->id !== 1) {
                // Si no es el usuario con ID 1, redirige o devuelve una respuesta no autorizada
                return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
            }
    
            return $next($request);
        });
    }





    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        $name = $request->search ?? '';
        $num = $request->num ?? 20;
        $orderBy = $request->order ?? 'id'; //image_posts_count
       
       

        $tags = Tag::withCount('imagePosts')->where('name','like','%' . $name . '%')->orWhere('translate_esp','like','%' . $name . '%')->orderBy($orderBy, 'asc') ->paginate($num)->withQueryString();   

       // dd($tags,$name);
        return Inertia::render('Tags/TagList', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tags/FormTag');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        $tag = new Tag();
        $tag->name = $request->name;
        $tag->translate_esp = $request->translate;
        $tag->wiki = $request->wiki;
        $tag->category = $request->type;

        

        $tag->save();

        if ($tag->category == 3) {

            $ulr_artist = $request->ulr_artist;
            $urlList = explode("\n", $ulr_artist);
            $urlList = array_map('trim', $urlList);
            $urlList = array_filter($urlList);

            $artist = new Artist();
            $artist->name = $request->name;
            $artist->tag_id = $tag->id;
            $artist->save();
            foreach($urlList as $url){
                $artis_url= new ArtistUrl();
                $artis_url->artist_id = $artist->id;
                $artis_url->url = $url;
                $artis_url->save();
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {

        $images = ImagePost::wherehas('tags', function ($q) use ($tag) {
            $q->where('tag_id',$tag->id);
        });//->inRandomOrder()->limit(10)->get();

        if (!Auth::user() || (Auth::user() && !Auth::user()->pegi_18)) {
            $images->where('pegi_18', '!=', true);
        }

        $images = $images->inRandomOrder()->limit(10)->get();

        $artist= null;
        $urls = null;

        if($tag->category == 3){
            $artist = $tag->artist;
            $urls = $artist->urls;
        }

       // $artist_url = //

        
        return Inertia::render('Tags/TagShow', compact('tag','images','artist','urls'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {

        $url_list = null;

        if($tag->category == 3){
            $urls = $tag->artist->urls->pluck('url')->toArray();

        // Convertir las URLs en un solo texto con saltos de línea
        $url_list = implode("\n", $urls);
        }


        return Inertia::render('Tags/FormTag',compact('tag','url_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {

        $tag->name = $request->name;
        $tag->translate_esp = $request->translate;
        $tag->wiki = $request->wiki;
        $tag->category = $request->type;


        $dataChanged = false;


        if ($tag->category == 3) {


            $ulr_artist = $request->ulr_artist;
            $newUrls = explode("\n", $ulr_artist);
            $newUrls = array_map('trim', $newUrls);
            $newUrls = array_filter($newUrls);

            $existingUrls = $tag->artist->urls->pluck('url')->toArray();
            
            if ($existingUrls !== $newUrls) {
                dd($tag->artist->id);
                $artist = ArtistUrl::where('artist_id', $tag->artist->id);
                dd($artist);
                // Eliminar todas las URLs existentes para este tag (opcional, depende de tu lógica)
                //ArtistUrl::where('artist_id', $tag->id)->delete();
    
                // Luego, guardar cada URL nueva en la base de datos
                /*foreach ($request['ulr_artist'] as $urlData) {
                    ArtistUrl::create([
                        'artist_id' => $tag->id,
                        'url' => $urlData['url'],
                    ]);
                }*/
    
                $dataChanged = true;
            }
        }

        

        if($dataChanged){
            dd('cambio las url pero no se aplica actualmente ');
        } else{
            $tag->save();
            return to_route('tags.show',$tag);
        }
        

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
