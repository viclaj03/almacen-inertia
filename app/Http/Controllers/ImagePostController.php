<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagePostRequest;
use App\Models\ImagePost;
use App\Models\ImageScan;//nuevo
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Mockery\Undefined;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use Intervention\Image\Facades\Image;
use PhpParser\Node\Stmt\TryCatch;

class ImagePostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show', 'search');
    }


    public function index(Request $request)
    {

        


        $num = $request->num ?? 10;

        $user = Auth::user();

        //dd(ImagePost::first());

        if ($user) {
            $images = ImagePost::select('id','name','imagen','pegi_18','private','light_version_imagen','secondary_tags')->where(function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->where('private', 0)
                        ->orWhere('user_post', $user->id);

                })->
                    whereDoesntHave('tags', function ($q) {
                        $q->where('tag_id', 16);
                    })->where('secondary_tags', 'not like', '%real_image%');

                if (!$user->pegi_18) {
                    $query->where('pegi_18', false);
                }
            })->orderBy('updated_at', 'desc')->paginate($num);
        } else {
            $images = ImagePost::where('private', false)
                ->where('pegi_18', false)
                ->whereDoesntHave('tags', function ($q) {
                    $q->where('tag_id', 16);
                })->where('secondary_tags', 'not like', '%real_image%')
                ->orderBy('updated_at', 'desc')
                ->paginate($num);
        }

       


        if ($user) {
            $favoriteImages = $user->favoriteImages()->pluck('image_id')->toArray();
            $images->each(function ($image) use ($favoriteImages) {
                $image->isFavorited = in_array($image->id, $favoriteImages);
            });
        }


        // dd($images);

        return Inertia::render('images/ImagesList', compact('images', ));
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
    public function store(ImagePostRequest $request)
    {
        
        $image = new ImagePost();

        $imagen = $request->image;

        $extension = $imagen->getClientOriginalExtension();
        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'webm'];

        if (in_array($extension, $extensionesVideo)) {
            $videoPath = $imagen->path();

            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => env('FFMPEG_BINARIES') .'ffmpeg.exe',
                // Ruta a ffmpeg en tu sistema
                'ffprobe.binaries' => ENV('FFMPEG_BINARIES') .'ffprobe.exe',
                // Ruta a ffprobe en tu sistema
                'timeout' => 3600,
                'ffmpeg.threads' => 12,
            ]);
            $video = $ffmpeg->open($videoPath);


            $duration = $video->getFormat()->get('duration');
            // Generar un número aleatorio dentro del rango de duración del video
            $randomTime = rand(1, $duration);


            // Captura un fotograma del video en el segundo 5 (ajusta según tus necesidades)
            $frame = $video->frame(TimeCode::fromSeconds($randomTime));

            // Guarda el fotograma como imagen en storage
            $lightVersionFilename = uniqid() . '.jpg';

            // Guarda el fotograma como imagen en el almacenamiento (public disk)
            $lightVersionPath = $lightVersionFilename;
            $frame->save(storage_path('app/public/light_versions/' . $lightVersionPath));

            // Asigna la ubicación de la imagen generada al modelo ImagePost
            $image->light_version_imagen = $lightVersionPath;

        }

        if (in_array($extension, $extensionesVideo)) {
            $imagenHash = hash_file('md5', $imagen->path());
        } else {
            try {
                $hasher = new ImageHash(new DifferenceHash());

                $hash = $hasher->hash($imagen->path());
                $imagenHash = $hash->toHex();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $nombreImagen = uniqid() . '.' . $extension;



        // Crear la subcarpeta con el formato año/mes
        $currentDatePath = date('Y/m'); // Ejemplo: '2024/08'

        

        // Guardar el post en la subcarpeta
        $postPath = $currentDatePath . '/' . $nombreImagen;

        

        




        $imagenHashMd5 = hash_file('md5', $imagen->path());

        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        $image->user_post = Auth::user()->id;
        $image->pegi_18 = $request->pegi18;
        $image->private = $request->private;
        $image->imagen = $postPath;
        $image->file_ext = $extension;
        $image->md5_hash = $imagenHashMd5;
        $image->file_size = $imagen->getSize();
        $image->description = $request->description;


        //$request->image->hashing_image;
        $image->imagen_hash = $imagenHash;


        if (preg_match('/https:\/\/danbooru\.donmai\.us\/posts\/\d+/', $image->danbooru_url)) {


            $image->danbooru_url = explode('?', $image->danbooru_url)[0];



            $urlJson = $image->danbooru_url . '.json';


            $response = Http::get($urlJson);
            if ($response->successful()) {
                $datos = $response->json();

                Tag::newTagGeneral($datos['tag_string_general']);
                Tag::newTagCharacters($datos['tag_string_character']);
                Tag::newTagCopyright($datos['tag_string_copyright']);


                $image->secondary_tags = $datos['tag_string'];

                $image->tag_count = $datos['tag_count'];
                $image->tag_count_general = $datos['tag_count_general'];
                $image->tag_count_artist = $datos['tag_count_artist'];
                $image->tag_count_character = $datos['tag_count_character'];


                if (!$image->original_url) {
                    $image->original_url = $datos['source'];
                }

                Tag::orderAndCountTags($image->secondary_tags);

            }
        } elseif ($request->tags_strings) {
            $image->secondary_tags = Tag::orderAndCountTags($request->tags_strings);
        }




        $image->new_position = true;
        $image->save();

        Storage::disk('public')->putFileAs('imagesPost/'. $currentDatePath, $imagen, $nombreImagen);

        //crear version ligera
        $lightVersionFilename = uniqid() . '_light.' . $extension;
        $lightVersionPath = 'app/public/light_versions/' . $lightVersionFilename;

        if (!in_array($extension, $extensionesVideo)) {
            Image::make($imagen->getRealPath())->resize(null, 360, function ($constraint) {
                $constraint->aspectRatio();
                //$constraint->upsize();
            })->save(storage_path($lightVersionPath));

            // Asignar la ubicación de la imagen ligera al modelo ImagePost
            $image->light_version_imagen = $lightVersionFilename;
            $image->save();
        }

        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $image->tags()->attach($tag['value']);
            }
        }


        if ($request->favorite)
            $image->favoritedBy()->attach(Auth::user()->id);


        //return to_route('images.show', $image);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImagePost $image)
    {

        
        

        $user = Auth::user();
        if ($image->private) {
            if (!auth()->user() || Auth::user()->id != $image->user_post)
                abort(403, 'Acceso denegado');
        }

        if (($image->pegi_18 && !Auth::user()) || (Auth::user() && !Auth::user()->pegi_18 && $image->pegi_18)) {
            abort(403, 'acceso denegado');
        }

        $image->isFavorited = $user ? $user->favoriteImages->contains($image->id) : false;
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
        foreach ($tags as $tag) {
            if ($tag->category == 0) {
                $tag_general[] = $tag;
            } elseif ($tag->category == 1) {
                $tag_copyright[] = $tag;
            } elseif ($tag->category == 2) {
                $tag_character[] = $tag;
            } elseif ($tag->category == 3) {

                $tag->isFavorite = $user ? $user->favoriteArtists->contains($tag->artist->id) : false;

                $tag->artist_id = $tag->artist->id;

                $tag_artist[] = $tag;
            } elseif ($tag->category == 4) {
                $tag_meta[] = $tag;
            }
        }




        $tags = explode(" ", $image->secondary_tags);

        $tag_string_general = [];
        $tag_string_character = [];
        $tag_string_copyright = [];
        $tag_string_artist = [];
        $tag_string_modelo = [];
        $tag_string_meta = [];
        $tag_string_unknow = [];
        foreach ($tags as $tag) {
            $tagSearch = Tag::where('name', str_replace('_', ' ', $tag))->first();


            if ($tagSearch === null) {
                $tag_string_unknow[] = $tag;

            } elseif ($tagSearch->category === 0) {
                $tag_string_general[] = $tagSearch;
            } elseif ($tagSearch->category === 1) {
                $tag_string_copyright[] = $tagSearch;
            } elseif ($tagSearch->category === 2) {
                $tag_string_character[] = $tagSearch;
            } elseif ($tagSearch->category === 3) {

                $tagSearch->isFavorite = $user ? $user->favoriteArtists->contains($tagSearch->artist->id) : false;

                $tagSearch->artist_id = $tagSearch->artist->id;

                $tag_string_artist[] = $tagSearch;
            } elseif ($tagSearch->category === 4) {
                $tag_string_meta[] = $tagSearch;
            } elseif ($tagSearch->category === 5) {


                $tagSearch->isFavorite = $user ? $user->favoriteModels->contains($tagSearch->model->id) : false;

                $tagSearch->modelo_id = $tagSearch->model->id;
                
                $tag_string_modelo[] = $tagSearch;


            }

        }


        //  dd($tags,"general",$tag_string_general,"character",$tag_string_character,"copyr",$tag_string_copyright,"artist",$tag_string_artist,'meta',$tag_string_meta,$tag_string_unknow);



        return Inertia::render(
            'images/ImagesShow',
            compact(
                'image',
                'tag_general',
                'tag_copyright',
                'tag_character',
                'tag_artist',
                'tag_meta',
                'tag_string_unknow',
                'tag_string_general',
                'tag_string_copyright',
                'tag_string_character',
                'tag_string_artist',
                'tag_string_meta',
                'tag_string_modelo'
            )
        );

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImagePost $image)
    {
        if ($image->user_post != null && $image->user_post != Auth::user()->id) {
            abort(403, 'acceso denegado');
        }
        $tags = $image->tags;

        $tag_list = [];
        foreach ($tags as $index => $tag) {
            $tag_list[] = [
                'value' => $tag->id,
                'label' => $tag->name,
            ];

        }


        return Inertia::render('images/FormImage', compact('image', 'tag_list'));



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImagePost $image)
    {
        if ($image->user_post != null && $image->user_post != Auth::user()->id) {
            abort(403, 'acceso denegado');
        }

        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        $image->private = $request->private;
        $image->pegi_18 = $request->pegi18;
        $image->description = $request->description;


        if ($request->getTags) {
            if (preg_match('/https:\/\/danbooru\.donmai\.us\/posts\/\d+/', $image->danbooru_url)) {

                $image->danbooru_url = explode('?', $image->danbooru_url)[0];
                $urlJson = $image->danbooru_url . '.json';

                $response = Http::get($urlJson);
                if ($response->successful()) {


                    $datos = $response->json();
                    Tag::newTagGeneral($datos['tag_string_general']);
                    Tag::newTagCharacters($datos['tag_string_character']);
                    Tag::newTagCopyright($datos['tag_string_copyright']);
                    $image->secondary_tags = $datos['tag_string'];
                    if (!$image->original_url) {
                        $image->original_url = $datos['source'];
                    }



                }
            }
        } elseif ($request->tags_strings) {
            $image->secondary_tags = $request->tags_strings;
        }


        $image->save();



        $image->tags()->detach();

        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $image->tags()->attach($tag['value']);
            }
        }

        return to_route('images.show', $image);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        

        $image = ImagePost::findorFail($id);
        if ($image->user_post != null && $image->user_post != Auth::user()->id) {
            abort(403, 'solo el dueño puede borrarla');
        }


        //dd($image->imagen);
        Storage::disk('public')->delete('imagesPost/' . $image->imagen);
        Storage::disk('public')->delete('light_versions/' . $image->light_version_imagen);

        $image->delete();

    }



    public function search(Request $request)
    {

        $user = Auth::user();

        $num = $request->num ?? 15;
        //dd($num);

        $tag_id = $request->tags;
        $tag_name = $request->tags_name;

        $tags_disable_id = $request->tags_disable;
        $tags_disable_name = $request->tags_name_disable;

        $imagenesSearch = ImagePost::with('tags')->where(function ($query) use ($user) {
            $query->where(function ($subQuery) use ($user) {
                $subQuery->where('private', 0);
                if ($user)
                    $subQuery->orWhere('user_post', $user->id);

            });


        });

        if ($tag_id) {
            foreach ($tag_id as $tag) {
                $imagenesSearch->wherehas('tags', function ($q) use ($tag) {
                    $q->where('tag_id', $tag);
                });
            }
            //desabilitadas
        }

        if ($tags_disable_id) {
            foreach ($tags_disable_id as $tag) {
                $imagenesSearch->whereDoesntHave('tags', function ($q) use ($tag) {
                    $q->where('tag_id', $tag);
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


        $tags_disable = new Collection();
        if ($tags_disable_id && $tags_disable_name) {
            foreach ($tags_disable_id as $index => $tag) {
                $tags_disable->push([
                    'value' => $tag,
                    'label' => $tags_disable_name[$index],
                ]);
            }
        }


        if (!Auth::user() || (Auth::user() && !Auth::user()->pegi_18)) {
            $imagenesSearch->where('pegi_18', '!=', true);
        }


        $tags_strings = $request->tags_strings;



        if ($tags_strings) {
            $tags_strings = preg_replace('/\s+/', ' ', trim($tags_strings));
            $tags2 = explode(' ', $tags_strings);

            foreach ($tags2 as $tag) {
                //provisional
                if ($tag == 'fav'){
                    $imagenesSearch->whereHas('favoritedBy', function ($query) {
                        $query->where('user_id', Auth::id());  // Filtrar por el ID del usuario autenticado
                    });
                }


                else if (strpos($tag, '-') === 0) {
                    $tag = strtolower(substr($tag, 1));

                    // Excluir términos negativos
                    $imagenesSearch->where(function ($query) use ($tag) {
                        $query->whereRaw('LOWER(secondary_tags) NOT LIKE ?', ['% ' . $tag . ' %'])
                            ->whereRaw('LOWER(secondary_tags) NOT LIKE ?', ['% ' . $tag])
                            ->whereRaw('LOWER(secondary_tags) NOT LIKE ?', [$tag . ' %']);
                    });
                } else {
                    $imagenesSearch->whereExists(function ($query) use ($tag) {
                        $query->selectRaw(1)

                            ->whereRaw('LOWER(secondary_tags) LIKE ?', ['% ' . $tag . ' %'])
                            ->orWhereRaw('LOWER(secondary_tags) LIKE ?', ['% ' . $tag])
                            ->orWhereRaw('LOWER(secondary_tags) LIKE ?', [$tag . ' %']);
                    });
                }

            }
        }






        
        $images = $imagenesSearch->latest()->paginate($num);
        $images->withQueryString();

        if (Auth::user()) {
            foreach ($images as $image) {
                $image->isFavorited = $user->favoriteImages->contains($image->id);
            }
        }
 

        return Inertia::render('images/ImagesList', compact('images', 'tags', 'tags_disable', 'num', 'tags_strings'));
    }

    public function searchByUniqHash(Request $request)
    {

        $threshold = 7; //normalmente 5  Umbral de similitud, ajusta según tus necesidades

        $images = ImagePost::select('*')
        ->selectRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$request->imagenHash', 16, 10)) AS similarity")
        ->whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$request->imagenHash', 16, 10)) <= $threshold")
        ->orderBy('similarity', 'asc')
        ->paginate(25);

        

        if (Auth::user()) {
            foreach ($images as $image) {
                $image->isFavorited = Auth::user()->favoriteImages->contains($image->id);
            }
        }


        return Inertia::render('images/ImagesList', compact('images'));

    }


    public function addFavorite(Request $request)
    {
        
        if($request->header('referer'));
        $image = ImagePost::findOrFail($request->id);
        

        if ($image->isFavoritedByUser()) {
            //dd($image->favoritedBy->where('user_id',Auth::user()->id));
            $image->favoritedBy()->detach(Auth::user()->id);
        } else {
            $image->favoritedBy()->attach(Auth::user()->id);
        }


        $referer = request()->header('referer');

        // Expresión regular para capturar una URL con formato 'http://almacen.test/images/{id}'
        $pattern = "/^http:\/\/almacen\.test\/images\/\d+$/";

        if (preg_match($pattern, $referer)) {
            dd('recarga la zona');
    
        } 

        
        
       // return to_route('images.show', $image->id);

    }




    public function newAddFavorite(Request $request){

        $image = ImagePost::findOrFail($request->id);

        
        if ($image->isFavoritedByUser()) {
            $image->favoritedBy()->detach(Auth::user()->id);
            $isFavorited = false;
            $message = 'Image unfavorited successfully';
        
        } else {
            $image->favoritedBy()->attach(Auth::user()->id);
            $isFavorited = true;
            $message = 'Image favorited successfully';
        }


        return response()->json([
            'favorited' => $isFavorited,
            'message' => $message,
            'image_id' => $image->id,
        ], 200);
    }


    public function seeFavorite(Request $request)
    {
        $user = Auth::user();
        $images = $user->favoriteImages()->orderByPivot('created_at', 'desc')->paginate(10);
        foreach ($images as $image) {
            $image->isFavorited = true;
        }

        return Inertia::render('images/ImagesList', compact('images'));

    }

    //only danbooru
    public function seeByUrl(Request $request)
    {
        
        ini_set('memory_limit', '2G');

        
        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'webm'];
        //controlar pixel hash
        $threshold = 7; //normalmente 5  Umbral de similitud, ajusta según tus necesidades
        $hasher = new ImageHash(new DifferenceHash());



        $images = [];
        $url_search = $request->url_search;
        $url_json = '';
        $url_params = '';
        $keyDanbooru = env('DANBOORU_KEY');
        $loginDanbooru = env('DANBOORU_LOGIN');

        if ($request->url_search) {
            $url_json = explode('?', $url_search)[0];
            if (count(explode('?', $url_search)) > 1)
                $url_params = explode('?', $url_search)[1];
            // $url_json = $url_search = explode('?', $url_search )[0];


            $url_json = $url_json . '.json?' . $url_params . "&api_key=$keyDanbooru&login=$loginDanbooru";

            try {
                $response = Http::get($url_json);

            } catch (\Throwable $th) {

                echo '<img class="" src="/storage/imagesPost/' . ImagePost::where('secondary_tags', 'like', '% 404 %')->inRandomOrder()->first()->imagen . '" alt="kisaki blue archive drawn by oshage osyage921  1cb0d5535665f6de3f9c053ccfac4ffa" width="500" height="600">';
                dd('¿hago un request form?', 'url no valida 🥵');

            }



            if ($response->successful()) {
                $images = $response->json();
                foreach ($images as &$image) {
                    

                    $imagePost = ImagePost::where('danbooru_url', 'https://danbooru.donmai.us/posts/' . $image['id'])->first();
                    $imagePost ? $image['exists'] = true : $image['exists'] = false;

                    if($image['rating'] == 'e' || $image['rating'] == 'q' ){
                        $image['pegi_18'] = true;
                        $image['private'] = true;
                    }else {
                        if( $image['rating'] == 's' )
                            $image['pegi_18'] = true;
                        else
                            $image['pegi_18'] = false;

                        $image['private'] = false;
                    }

                    if ($image['exists']){
                        
                    
                        $image['favorite'] = $imagePost->isFavoritedByUser();
                        $image['pegi_18'] = (boolean) $imagePost->pegi_18;
                        $image['private'] = (boolean) $imagePost->private;
                    }
                    else
                        $image['favorite'] = false;
                    

                    $image['extra_tags'] = [];

                    if (!$image['exists']) {


                        $scan = ImageScan::where('source', 'danbooru')
                        ->where('source_id', $image['id'])
                        ->first();




                        if ($scan){

                            $similarImages = ImagePost::whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$scan->image_hash;ash', 16, 10)) <= $threshold")
                            ->get();

                            $image['imagen_hash'] = $scan->image_hash;
                            $image['similar_count'] = $similarImages->count();
                        } else {
                
                            try {
                                $imagen_hash = Http::get($image['file_url'])->body();

                                $file_url = $image['file_url'];

                                //$imageContent = Http::get($file_url)->body();
                                //$imageName = uniqid() . '.' . $image['file_ext'];


                                $imagenHash = $hasher->hash($imagen_hash);
                       
                                $similarImages = ImagePost::whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$imagenHash', 16, 10)) <= $threshold")
                                ->get();
                                $image['imagen_hash'] = $imagenHash;
                                $image['similar_count'] = $similarImages->count();

                                ImageScan::create([
                                    'source' => 'danbooru',
                                    'source_id' => $image['id'],
                                    'file_url' => $image['file_url'],
                                    'image_hash' => $imageHash,
                                ]);



                            } catch (\Throwable $th) {

                                $image['imagen_hash'] = '';
                                $image['similar_count'] = 'no tiene';
                            }
                        }

                    } else {
                        
                        $image['imagen_hash'] = $imagePost->imagen_hash??'ERRROR';
                        $image['similar_count'] = $imagePost->imagen_hash?'Subido':'SUBIDO PERO HAS MAL';
                    }




                }
                unset($image);


            }
        }
        


        //return Inertia::render('images/FormImageUrl',compact('keyDanbooru','loginDanbooru'));
        return Inertia::render('images/FormImageUrl', compact('images', 'url_search'));
    }


    public function uploadUrl(Request $request)
    {
        
        $url_danbooru = "https://danbooru.donmai.us/posts/";
        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'webm'];
        $keyDanbooru = env('DANBOORU_KEY');
        $loginDanbooru = env('DANBOORU_LOGIN');


        foreach ($request->selectedImages as $imagen) {

            
            

            $response = Http::get($url_danbooru . $imagen['id'] . ".json?&api_key=$keyDanbooru&login=$loginDanbooru");


            if ($response->successful()) {
                $imageData = $response->json();


                $file_url = $imageData['file_url'];
                $imageContent = Http::get($file_url)->body();
                $extension = pathinfo($file_url, PATHINFO_EXTENSION);
                $nombreImagen = uniqid() . '.' . $extension;

                Tag::newTagGeneral($imageData['tag_string_general']);
                Tag::newTagCharacters($imageData['tag_string_character']);
                Tag::newTagCopyright($imageData['tag_string_copyright']);

            
                
                $image = new ImagePost();
                $image->name = 'Subido por url'; // Deja en blanco de momento
                if (strlen($imageData['source']) > 255){
                    $image->original_url = 'URL MUY LARGA';
                }else{
                    $image->original_url = $imageData['source'] ?? '';
                }


                // Crear la subcarpeta con el formato año/mes
                $currentDatePath = date('Y/m'); // Ejemplo: '2024/08'

        

                // Guardar el post en la subcarpeta
                $postPath = $currentDatePath . '/' . $nombreImagen;
                




                $image->danbooru_url = $url_danbooru . $imageData['id'];
                $image->user_post = Auth::user()->id;
                $image->pegi_18 = $imagen['pegi_18']; 
                $image->private =  $imagen['private']; 
                $image->imagen = $postPath;
                $image->file_ext = $extension;
                $image->md5_hash = $imageData['md5'];
                $image->file_size = $imageData['file_size'];
                $image->description = ''; // Deja en blanco de momento
                $image->imagen_hash = $imagen['imagen_hash'];

                $image->secondary_tags = $imageData['tag_string'];
                Tag::orderAndCountTags($image->secondary_tags);

                $image->tag_count = $imageData['tag_count'];
                $image->tag_count_general = $imageData['tag_count_general'];
                $image->tag_count_artist = $imageData['tag_count_artist'];
                $image->tag_count_character = $imageData['tag_count_character'];
                //dd($image->secondary_tags);
                $image->new_position = true;
                $image->save();

                
                Storage::disk('public')->put('imagesPost/' . $postPath, $imageContent);

 
                //crear version ligera

                if (!in_array($extension, $extensionesVideo)) {
                    $lightVersionFilename = uniqid() . '_light.' . $extension;
                    $lightVersionPath = 'app/public/light_versions/' . $lightVersionFilename;

                    Image::make(storage_path('app/public/imagesPost/' . $postPath))
                        ->resize(null, 360, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save(storage_path($lightVersionPath));

                    // Asignar la ubicación de la imagen ligera al modelo ImagePost
                    $image->light_version_imagen = $lightVersionFilename;
                    $image->save();
                }


                
                foreach ($imagen['extra_tags'] as $tag) {
                        $image->tags()->attach($tag);
                }

                   
               if($imagen['favorite']){
                $image->favoritedBy()->attach(Auth::user()->id);
               } 

            }

        }
        //$imageIds = collect($request->selectedImages)->pluck('id');

        dd('fin');

    }



}