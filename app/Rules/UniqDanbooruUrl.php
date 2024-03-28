<?php

namespace App\Rules;

use App\Models\ImagePost;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class UniqDanbooruUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value){
            return;
        }

       

        if(preg_match('/https:\/\/danbooru\.donmai\.us\/posts\/\d+/',$value)){


            $value =  explode('?',$value)[0];
        }

       

        $image = ImagePost::where('danbooru_url','=',$value)->first();



        
        if($image){
            $fail('ya existe este enlace puedes verlo  <a target="_blank" class="text-white" href="' . route('images.show', $image) . '">aquí</a>. ' ) ;
        }
        
        
    }
}
