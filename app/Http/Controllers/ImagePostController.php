<?php

namespace App\Http\Controllers;

use App\Models\ImagePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


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
        $images = ImagePost::paginate(1)->onEachSide(1);

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
        Storage::disk('public')->putFileAs('imagesPost', $imagen, $nombreImagen);

        $image = new ImagePost();
        $image->name = $request->name;
        $image->original_url = $request->original_url;
        $image->danbooru_url = $request->danbooru_url;
        $image->user_post = Auth::user()->id;
        $image->pegi_18 = $request->pegi18;
        $image->imagen = $nombreImagen;
       
       
        $image->imagen_hash = $imagenHash; // aqui el hash
        $image->save();
        
        return to_route('images.show',$image->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $image = ImagePost::findorFail($id);
        dd(1);
        echo "<img src= \"/storage/imagesPost/$image->imagen\" />";
        dd($image);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $image = ImagePost::findorFail($id);
        dd($image);
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
        return response()->json(['message' => 'La imagen ha sido eliminada correctamente']);

        dd(99);
        $image = ImagePost::findorFail($id);
        dd($image);

    // Eliminar el archivo de imagen del sistema de almacenamiento
    Storage::disk('public')->delete('imagesPost/' . $image->imagen);

    // Eliminar el registro de la imagen de la base de datos
    $image->delete();

    // Retornar una respuesta o realizar otras acciones necesarias
    // ...

    return response()->json(['message' => 'La imagen ha sido eliminada correctamente']);
        
    }
}
