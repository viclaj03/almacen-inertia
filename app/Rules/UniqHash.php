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


        $threshold = 5; // Umbral de similitud, ajusta según tus necesidades

        $hasher = new ImageHash(new DifferenceHash());
        $imagenHash =  $hasher->hash($value->path());

        $similarImages = ImagePost::whereRaw("BIT_COUNT(CONV(imagen_hash, 16, 10) ^ CONV('$imagenHash', 16, 10)) <= $threshold")
            ->first();//cambiar para varias


        //dd($similarImages->id);
        
        $image = ImagePost::where('imagen_hash','=',$imagenHash)->first();
        if($similarImages){
            
            $fail('ya existe este hash puedes verlo  <a target="_blank" class="text-white" href="' . route('images.show', $similarImages->id) . '">aquí</a>.');
        }

    }
}
