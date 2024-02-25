<?php

namespace App\Http\Controllers;

use App\Models\VideoPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;


class VideoPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */





    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');

        $this->middleware(function ($request, $next) {
            // Verifica si el usuario está autenticado y su ID es igual a 1
            if (!auth()->check() || auth()->user()->id !== 1) {
                // Si no es el usuario con ID 1, redirige o devuelve una respuesta no autorizada
                return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
            }

            return $next($request);
        });
    }


    public function index()
    {
        $videos = VideoPost::where('pegi_18', false)->orWhere('pegi_18', Auth::user()->pegi_18)->latest()->paginate(300 );

        return Inertia::render('VideoPost/VideoList', compact('videos', ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('VideoPost/FormVideos');


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        //for ($i=0; $i < 5; $i++){
        $videoPost = new VideoPost();
        $videoPost->title = $request->title;
        $videoPost->description = "Ninguna";
        $videoPost->url_original = $request->original_url;

        $videoPost->pegi_18 = $request->pegi18;
        $videoPost->private = false;
        $videoPost->user_post = Auth::user()->id;

        $video = $request->video;
        //dd($video);
        $nombrevideo = uniqid() . '.' . $video->getClientOriginalExtension();

        $videoPost->video_path = $nombrevideo;

        //prin

        $videoPath = $video->path();

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => 'C:\Users\victo\OneDrive\Escritorio\dan\ffmpeg-6.0-essentials_build\bin\ffmpeg.exe',
            // Ruta a ffmpeg en tu sistema
            'ffprobe.binaries' => 'C:\Users\victo\OneDrive\Escritorio\dan\ffmpeg-6.0-essentials_build\bin\ffprobe.exe',
            // Ruta a ffprobe en tu sistema
            'timeout' => 3600,
            'ffmpeg.threads' => 12,
        ]);
        $videoff = $ffmpeg->open($videoPath);


        $duration = $videoff->getFormat()->get('duration');
        // Generar un número aleatorio dentro del rango de duración del video
        $randomTime = rand(1, $duration);


        // Captura un fotograma del video en el segundo 5 (ajusta según tus necesidades)
        $frame = $videoff->frame(TimeCode::fromSeconds($randomTime));

        // Guarda el fotograma como imagen en storage
        $lightVersionFilename = uniqid() . '.jpg';

        // Guarda el fotograma como imagen en el almacenamiento (public disk)
        $lightVersionPath = $lightVersionFilename;

        $frame->save(storage_path('app/public/light_versions2/' . $lightVersionPath));

        // Asigna la ubicación de la imagen generada al modelo ImagePost
        $videoPost->image_path  = $lightVersionPath;


        //fin

        $videoPost->save();
        Storage::disk('public')->putFileAs('VideosPost', $video, $nombrevideo);

       // }



    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPost $video)
    {
        return Inertia::render('VideoPost/VideoShow', compact('video', ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoPost $videoPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VideoPost $videoPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $videoPost = VideoPost::findorFail($id);

        Storage::disk('public')->delete('VideosPost/' . $videoPost->video_path);

        Storage::disk('public')->delete('light_versions2/' . $videoPost->image_path);

        // Eliminar el registro de la imagen de la base de datos
        $videoPost->delete();
    }
}