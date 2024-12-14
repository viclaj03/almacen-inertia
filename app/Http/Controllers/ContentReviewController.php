<?php

namespace App\Http\Controllers;

use App\Jobs\ReviewTagsJob;
use App\Jobs\TestJob;
use App\Mail\TestEmail;
use App\Models\ImagePost;
use App\Models\Tag;
use App\Models\VideoPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;
use LDAP\Result;

use function PHPUnit\Framework\isEmpty;

class ContentReviewController extends Controller
{
    public function reviewTagsCount(){


        //ReviewTagsJob::dispatch();
        Log::info('Despachando el Job ReviewTagsJob.');
        TestJob::dispatch();
        Log::info('Job ReviewTagsJob ha sido enviado a la cola.');
        return response()->json(['message' => 'El proceso se ha enviado a la cola.']);
    }


    public function reviewImagesCount()
    {

       /* $duplicates = ImagePost::where('danbooru_url', 'like', '%?%')->get();

        foreach ($duplicates as $duplicate) {
            echo "<a data-v-f2b36d1a='' target='_blank' class='flex' href='http://almacen.test/images/{$duplicate->id}'>$duplicate->id $duplicate->name</a><br>";
        }
        die();*/


      

       


        $cantidad = 10;

        $images = ImagePost::where('file_size','<=',20971520)->inRandomOrder()->take($cantidad)->get();
        
        Mail::to(Auth::user()->email)->send(new TestEmail($images,$cantidad));

        //TestJob::dispatch();
        

        
        $files_video = Storage::disk('public')->files('VideosPost');
        $videoInFolder = array_map('basename',$files_video);


        
        $videosInDatabase = VideoPost::pluck('video_path')->toArray();

        $videoNotIndataba = array_diff($videoInFolder,$videosInDatabase);

        // Obtener el listado de archivos en el directorio
        $files = Storage::disk('public')->files('imagesPost');

        $imagesInFolder = array_map('basename', $files);

        $imagesInDatabase = ImagePost::pluck('imagen')->toArray();

        $imagesNotInDatabase = array_diff($imagesInFolder, $imagesInDatabase);


        $tag_count = Tag::count();

    // Mostrar las imágenes que están en la carpeta pero no en la base de datos
    

    return response()->json([
        'images_not_in_database' => $imagesNotInDatabase,
        'videos_not_in_database'=>$videoNotIndataba,
        'coun_images_folder' => count($imagesInFolder),
        'count_Database_images' => count($imagesInDatabase),
        'Diferencia'=> count($imagesInFolder) - count($imagesInDatabase),
        'Tag count'=>$tag_count
    ]);

        
    }



    public function reversePostSearch(Request $request){
        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'webm'];
        $threshold = 7; //normalmente 5  Umbral de similitud, ajusta según tus necesidades

        $ulrResuts = [];
        $md5HashResults = [];
        $imageResults = [];

        if ($request->url)
            $ulrResuts = ImagePost::where('danbooru_url',$request->url)->orWhere('original_url',$request->url)->get();

        if($request->hahs)
            $md5HashResults = ImagePost::where('md5_hash',$request->hash)->get();
    
        
        if($request->image){
            $md5_hash = hash_file('md5', $request->image->path());
            $imageResults  = ImagePost::where('md5_hash',$md5_hash)->get();
        }

        if($request->image && $imageResults->isEmpty() && !in_array($request->image->getClientOriginalExtension(),$extensionesVideo) ){
            $hasher = new ImageHash(new DifferenceHash());
            $imagenHash =  $hasher->hash($request->image->path());
            $imageResults = ImagePost::whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$imagenHash', 16, 10)) <= $threshold");
        }
        

        return response()
                    ->json([
                            'ulrResuts' => $ulrResuts,
                            'md5HashResults'=>$md5HashResults,
                            'imageResults'=>$imageResults,
                            'results'=> count($ulrResuts) || count($imageResults) || count($md5HashResults)
                    ]);
    }


}
