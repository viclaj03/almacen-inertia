<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Artist;
use App\Models\ArtistUrl;
use App\Models\ImagePost;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as RequestFilter;

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

        /*$tags = Tag::all();
        
        foreach($tags as $tag){
            $name =  str_replace(' ','_',$tag->name);

            $images = ImagePost::where('secondary_tags', 'LIKE', '%' . $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', '% ' . $name)
        ->orWhere('secondary_tags','=', $name)->get();
        
        $tag->post_count = $images->count();
        $tag->save();
            
        }
        dd($tags);*/
 
        $name = $request->search ?? '';
        $num = $request->num ?? 15;
        $orderBy = $request->order  ?? 'id'; //'image_posts_count';
        $tags = Tag::withCount('imagePosts')
        ->where(function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('translate_esp', 'like', '%' . $name . '%');
                //->orWhere('wiki', 'like', '%' . $name . '%');
        });
        
        if($request->type  > -1){
          $tags =   $tags->where('category',$request->type);
        }

        $filters = RequestFilter::all(['search', 'type']);
        
        $tags = $tags->orderBy($orderBy, 'desc') ->paginate($num)->withQueryString();   
        return Inertia::render('Tags/TagList' , compact('tags','filters'));
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
        $tag->wiki = $request->wiki ?? '';
        $tag->category = $request->type;
        $tag->others_names = $request->others_names;

        
        $tag->save();

        if ($tag->category == 3) {

            $ulr_artist = $request->ulr_artist;
            $urlList = explode("\n", $ulr_artist);
            $urlList = array_map('trim', $urlList);
            $urlList = array_filter($urlList);

            $artist = new Artist();
            $artist->name = $request->name;
            $artist->tag_id = $tag->id;
            $artist->urls = $request->ulr_artist;
            $artist->save();
            /*foreach($urlList as $url){
                $artis_url= new ArtistUrl();
                $artis_url->artist_id = $artist->id;
                $artis_url->url = $url;
                $artis_url->save();
            }*/
        } else if($tag->category == 5){
            $model = new Modelo();
            $model->name = $request->name;
            $model->tag_id = $tag->id;
            
            $model-> urls =  $request->ulr_artist;
            $model->save();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {

        $name = str_replace(' ','_',$tag->name);
        
        $images = ImagePost::where('secondary_tags', 'LIKE', '%' . $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', '% ' . $name)
        ->orWhere('secondary_tags','=', $name);

        if (!Auth::user() || (Auth::user() && !Auth::user()->pegi_18)) {
            $images->where('pegi_18', '!=', true);
        }
        
        $images = $images->inRandomOrder()->limit(10)->get();

       
        $artist= null;
        $urls = null;

        if($tag->category == 3){
            $artist = $tag->artist;
            $urls = $artist->urls_old;
        }elseif($tag->category == 5){

            $urls =explode("\n",$tag->model->urls);

            
        }

        $tag = $tag->loadCount('imagePosts');
        
        return Inertia::render('Tags/TagShow', compact('tag','images','artist','urls'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {

        $url_list = null;

        if($tag->category == 3){
            $urls = $tag->artist->urls_old->pluck('url')->toArray();

        // Convertir las URLs en un solo texto con saltos de línea
        $url_list = implode("\n", $urls);
        } elseif($tag->category == 5){
            $url_list = Modelo::where('tag_id',$tag->id)->first()->urls;
            
        }


        return Inertia::render('Tags/FormTag',compact('tag','url_list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {



        

        /*if($tag->category == 3 && $request->type != 3){
            dd('no se puede cambiar un artista de categoria  momento');
        }*/

        $tag->name = $request->name;
        $tag->translate_esp = $request->translate;
        $tag->wiki = $request->wiki ?? '';

        

        


        if ($request->type == 3) {

            $artist = Artist::where('tag_id', $tag->id)->first();
            if(!$artist){
                if($tag->category == 5){
                    $artist = Artist::where('tag_id', $tag->id)->first()->delete();
                }
                $artist = new artist();
                $artist->name = $request->name;
                $artist->tag_id = $tag->id;
                $artist-> urls =  $request->ulr_artist;
                $artist->save();
            } else{
                $artist->name = $request->name;
                $artist->tag_id = $tag->id;
                $artist-> urls =  $request->ulr_artist;
                $artist->save();
            }
            $artist->name = $request->name;
            $artist->urls = $request->ulr_artist;
           // $artist->save();            
        } else if($request->type == 5){
            $modelo = Modelo::where('tag_id',$tag->id)->first();
            if(!$modelo){
                if($tag->category == 3){
                    $artist = Artist::where('tag_id', $tag->id)->first()->delete();
                }
                $modelo = new Modelo();
                $modelo->name = $request->name;
                $modelo->tag_id = $tag->id;
                $modelo-> urls =  $request->ulr_artist;
                $modelo->save();
            } else{
                $modelo->name = $request->name;
                $modelo->tag_id = $tag->id;
                $modelo-> urls =  $request->ulr_artist;
                $modelo->save();
            }
        }

        



            $tag->category = $request->type;
            $tag->save();
            return to_route('tags.show',$tag);
        
        

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        

        $imagesDirect = ImagePost::wherehas('tags', function ($q) use ($tag) {
            $q->where('tag_id',$tag->id);
        })->get();

        $images = ImagePost::paginate(9);
        if($imagesDirect->count()){
            dd($imagesDirect,'primero');
        }

        $name = str_replace(' ','_',$tag->name);

        $images = ImagePost::where('secondary_tags', 'LIKE', '%' . $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', $name . ' %')
        ->orWhere('secondary_tags', 'LIKE', '% ' . $name)
        ->orWhere('secondary_tags','=', $name)->get();

        
        if($images->count()){
            dd($images,9,$tag->name);
        }
        $tag->delete();
    }
}
