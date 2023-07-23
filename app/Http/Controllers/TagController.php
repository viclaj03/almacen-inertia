<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Artist;
use App\Models\ArtistUrl;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::withCount('imagePosts')->paginate(10);   

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
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
