<?php

namespace App\Http\Controllers;

use App\Models\VideoPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $videos = VideoPost::where('pegi_18' ,false)->orWhere('pegi_18',Auth::user()->pegi_18)->paginate(10);

        return Inertia::render('VideoPost/VideoList',compact('videos',));

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
        $videoPost = new VideoPost();
        $videoPost->title = $request->title;
        $videoPost->description = "Ninguna";
        
        $videoPost->pegi_18 = $request->pegi18;
        $videoPost->private = false;

        $video = $request->video;
        $nombrevideo = uniqid().'.'.$video->getClientOriginalExtension();

        $videoPost->video_path = $nombrevideo;
        $videoPost->image_path = $videoPost->pegi_18?'https://img.freepik.com/vector-premium/simbolo-advertencia-signo-menores-18-anos-mayores-18-solo-censurados-dieciocho-anos-mayores-contenido-adultos-prohibido_41737-1113.jpg?w=2000':'https://actualizadoscomunicacion.com/wp-content/uploads/2017/06/youtube-2172750_960_720-794x397.png';
        $videoPost->save();
        Storage::disk('public')->putFileAs('VideosPost', $video, $nombrevideo);


    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPost $video)
    {
        return Inertia::render('VideoPost/VideoShow',compact('video',));
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

        Storage::disk('public')->delete('VideosPost/' . $videoPost ->video_path);

    // Eliminar el registro de la imagen de la base de datos
    $videoPost ->delete();
    

    }
}
