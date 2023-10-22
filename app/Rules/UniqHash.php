<?php

namespace App\Rules;

use App\Models\ImagePost;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class UniqHash implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $extensionesVideo = ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'webm'];

        if(in_array($value->getClientOriginalExtension(),$extensionesVideo) ){
            return;
        }

        

        $threshold = 7; //normalmente 5  Umbral de similitud, ajusta según tus necesidades

        $hasher = new ImageHash(new DifferenceHash());
        $imagenHash =  $hasher->hash($value->path());
        $similarImages = ImagePost::whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$imagenHash', 16, 10)) <= $threshold")
            ->get();//cambiar para varias
           // dd($similarImages);

        //$image = ImagePost::where('imagen_hash','=',$imagenHash)->first();
        if($similarImages->count() && request('hash_ignore') != 1){
            
            $fail('ya existe este hash puedes verlo  <a target="_blank" class="text-white" href="' . route('images.show', $similarImages->first()) . '">aquí</a>. ademade de otras  ->'  .'  <a target="_blank" class="text-white" href="' . route('images.uniqHash', ['imagenHash' => $imagenHash]) . '">' . ($similarImages->count() -1) . '</a> <br>hash: '. $imagenHash);
        }
      

        $value->hashing_image = $imagenHash->toHex();

    }
}
