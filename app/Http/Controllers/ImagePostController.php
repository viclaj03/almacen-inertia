<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagePostRequest;
use App\Models\ImagePost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Mockery\Undefined;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ImagePostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
         $this->middleware('auth')->except('index','show','search');
    }


    public function index(Request $request)
    {

        

        $num = $request->num ?? 10;

        $user = Auth::user();

        if ($user) {
            $images = ImagePost::where(function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->where('private', 0)
                        ->orWhere('user_post', $user->id);
                });

                if (!$user->pegi_18) {
                    $query->where('pegi_18', false);
                }
            })->latest()->paginate($num);
        } else {
            $images = ImagePost::where('private', 0)
                ->where('pegi_18', false)
                ->latest()
                ->paginate($num);
        }

        // $images = ImagePost::paginate(1);


        //  $images = ImagePost::where('pegi_18' ,false)->orWhere('pegi_18',Auth::user()->pegi_18)->latest()->paginate(10)->onEachSide(1);


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
        //dd('pruebas');

        $image = new ImagePost();

        $imagen = $request->image;
        $extension = $imagen->getClientOriginalExtension();
        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv'];

        if (in_array($extension, $extensionesVideo)) {
            $videoPath = $imagen->path();
            
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => 'C:\Users\victo\OneDrive\Escritorio\dan\ffmpeg-6.0-essentials_build\ffmpeg-6.0-essentials_build\bin\ffmpeg.exe',
                // Ruta a ffmpeg en tu sistema
                'ffprobe.binaries' => 'C:\Users\victo\OneDrive\Escritorio\dan\ffmpeg-6.0-essentials_build\ffmpeg-6.0-essentials_build\bin\ffprobe.exe',
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
            $lightVersionPath =  $lightVersionFilename;
            $frame->save(storage_path('app/public/light_versions/' . $lightVersionPath));

            // Asigna la ubicación de la imagen generada al modelo ImagePost
            $image->light_version_imagen = $lightVersionPath;
            



        } 

        if (in_array($extension, $extensionesVideo)) {
        $imagenHash = hash_file('md5', $imagen->path());
        } else {
            $hasher = new ImageHash(new DifferenceHash());
            $hash = $hasher->hash($imagen->path());
            $imagenHash = $hash->toHex();
        }
        $nombreImagen = uniqid() . '.' . $extension;


        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        $image->user_post = Auth::user()->id;
        $image->pegi_18 = $request->pegi18;
        $image->private = $request->private;
        $image->imagen = $nombreImagen;
        $image->file_ext = $extension;
        $image->file_size = $imagen->getSize();


        $image->imagen_hash = $imagenHash;
        
        $image->save();
        Storage::disk('public')->putFileAs('imagesPost', $imagen, $nombreImagen);



     if($request->tags){
             foreach($request->tags as $tag){
                 $image->tags()->attach($tag['value']);
              }
         } 

        // dd('ok');


        //return to_route('images.show', $image);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImagePost $image)
    {


        
        
           

        if ($image->private) {
            if (!auth() || Auth::user()->id != $image->user_post)
                abort(403, 'Acceso denegado');
        }

        if (($image->pegi_18 && !Auth::user()) || (Auth::user() && !Auth::user()->pegi_18 && $image->pegi_18)) {
            abort(403, 'Acceso denegado');
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
        foreach ($tags as $tag) {
            if ($tag->category == 0) {
                $tag_general[] = $tag;
            } elseif ($tag->category == 1) {
                $tag_copyright[] = $tag;
            } elseif ($tag->category == 2) {
                $tag_character[] = $tag;
            } elseif ($tag->category == 3) {
                $tag_artist[] = $tag;
            } elseif ($tag->category == 4) {
                $tag_meta[] = $tag;
            }
        }
        return Inertia::render('images/ImagesShow', compact('image', 'tag_general', 'tag_copyright', 'tag_character', 'tag_artist', 'tag_meta'));

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


        Storage::disk('public')->delete('imagesPost/' . $image->imagen);
        Storage::disk('public')->delete('light_versions/' . $image->light_version_imagen);


        $image->delete();

    }



    public function search(Request $request)
    {

        

        $num = $request->num ?? 15;

        $tag_id = $request->tags;
        $tag_name = $request->tags_name;

        $tags_disable_id = $request->tags_disable;
        $tags_disable_name = $request->tags_name_disable;


        $imagenesSearch = ImagePost::with('tags');

        if ($tag_id) {
            foreach ($tag_id as $tag) {
                $imagenesSearch->wherehas('tags', function ($q) use ($tag) {
                    $q->where('tag_id', $tag);
                });
            }
            //desabilitadas
        }
        
        if($tags_disable_id){
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

        $images = $imagenesSearch->latest()->paginate($num);
        $images->withQueryString();






        
        return Inertia::render('images/ImagesList', compact('images', 'tags','tags_disable'));
    }















    public function uploadByUrl(Request $request)
    {


        if ($request->url_search) {
            //aqui el scraping a twitter
        } else {

            return Inertia::render('images/FormImageUrl');

        }
    }




}