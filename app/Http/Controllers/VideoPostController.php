<?php

namespace App\Http\Controllers;

use App\Models\VideoPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VideoPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $videos = VideoPost::paginate(10);

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
        $videoPost->url = $request->original_url;

        $video = $request->video;
        $nombrevideo = uniqid().'.'.$video->getClientOriginalExtension();

       Storage::disk('public')->putFileAs('VideosPost', $video, $nombrevideo);
        $videoPost->video_path = $nombrevideo;
        $videoPost->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPost $videoPost)
    {
        dd($videoPost);
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
