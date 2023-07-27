<?php

namespace App\Http\Controllers;

use App\Models\ImagePost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Mockery\Undefined;

class ImagePostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
        // $this->middleware('auth')->except('index','show');
     }


    public function index()
    {



        $images = ImagePost::where('pegi_18' ,false)->orWhere('pegi_18',Auth::user()->pegi_18)->latest()->paginate(10)->onEachSide(1);


        return Inertia::render('images/ImagesList',compact('images',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('images/FormImage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        $imagen = $request->image;
        $imagenHash = hash_file('md5', $imagen->path());
        $nombreImagen = uniqid().'.'.$imagen->getClientOriginalExtension();

        $image = new ImagePost();
        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        $image->user_post = Auth::user()->id;
        $image->pegi_18 = $request->pegi18;
        $image->imagen = $nombreImagen;
        $image->file_ext = $imagen->getClientOriginalExtension();
        $image->file_size = $imagen->getSize();
       
       
        $image->imagen_hash = $imagenHash; // aqui el hash
        $image->save();
        Storage::disk('public')->putFileAs('imagesPost', $imagen, $nombreImagen);

       

        if($request->tags){
            foreach($request->tags as $tag){
                $image->tags()->attach($tag['value']);
             }
        } 
        



        return to_route('images.show',$image);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImagePost $image)
    {

        if(($image->pegi_18 && !Auth::user()) || (!Auth::user()->pegi_18 && $image->pegi_18) ){
            abort(403,'Acceso denegado');
        }

        $tags = $image->tags;
        $tags->map(function ($tag) {
            $tag->loadCount('imagePosts');
            return $tag;
        });
        $tag_general = [];
        $tag_character = [];
        $tag_artist = [];
        $tag_copyright = [];
        $tag_meta = [];
        foreach($tags as $tag ){
            if($tag->category  == 0){
                $tag_general[] = $tag; 
            } elseif($tag->category == 1 ){
                $tag_copyright[] = $tag;
            } elseif($tag->category == 2){
                $tag_character[] = $tag;
            } elseif($tag->category == 3){
                $tag_artist[] = $tag;
            }elseif($tag->category == 4) {
                $tag_meta[] = $tag;
            }
        }
        return Inertia::render('images/ImagesShow',compact('image','tag_general','tag_copyright','tag_character','tag_artist','tag_meta'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImagePost $image)
    {
        $tags = $image->tags;

        $tag_list = [];
        foreach ($tags as $index => $tag) {
            $tag_list [] = [
                'value' => $tag->id,
                'label' => $tag->name,
            ];
            
        }
        
       
        return Inertia::render('images/FormImage',compact('image','tag_list'));

        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImagePost $image)
    {
        
        //$image = ImagePost::findorFail($id);  
        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        //$image->user_post = Auth::user()->id;
        $image->pegi_18 = $request->pegi18;
        $image->save();


        $image->tags()->detach();

        if($request->tags){
            foreach($request->tags as $tag){
                $image->tags()->attach($tag['value']);
             }
        } 

        return to_route('images.show',$image);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = ImagePost::findorFail($id);   
        
        Storage::disk('public')->delete('imagesPost/' . $image->imagen);
        $image->delete();
       
    }

 

    public function search(Request $request )
{
    $tag_id = $request->tags;
    $tag_name = $request->tags_name;


    $imagenesSearch = ImagePost::with('tags');

    if($tag_id){
        foreach ($tag_id as $tag) {        
            $imagenesSearch->wherehas('tags', function ($q) use ($tag) {
                $q->where('tag_id',$tag);
            });
        }

    }

    $tags = new Collection();
    if ($tag_id && $tag_name) {
        foreach ($tag_id as $index => $tag) {
            $tags->push([
                'value' => $tag,
                'label' => $tag_name[$index],
            ]);
        }
    }
    



    if(!Auth::user() || (Auth::user() && !Auth::user()->pegi_18) ){
        $imagenesSearch->where('pegi_18','!=',true);
    }

    $images = $imagenesSearch->paginate(15);

    $images->withQueryString();




    
    return Inertia::render('images/ImagesList',compact('images','tags'));
}  















public function uploadByUrl(Request $request){


    if($request->url_search){
        //aqui el scraping a twitter
    }else {

    return Inertia::render('images/FormImageUrl');

    }
}




}
