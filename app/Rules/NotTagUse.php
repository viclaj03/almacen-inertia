<?php

namespace App\Rules;

use App\Models\ImagePost;
use App\Models\Tag;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class NotTagUse implements ValidationRule
{
    
    protected $oldTag;

    public function __construct(Tag $var) {
        $this->oldTag = $var;
    }
   
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {



        
        if ($this->oldTag->name != $value){
            $name = str_replace(' ','_',$this->oldTag->name);
            $images = ImagePost::where('secondary_tags', 'LIKE', '% ' . $name . ' %')
            ->orWhere('secondary_tags', 'LIKE', $name . ' %')
            ->orWhere('secondary_tags', 'LIKE', '% ' . $name)
            ->orWhere('secondary_tags','=', $name)->get();


            
            if($images->count()){
                $fail('Hay  <a target="_blank" class="text-white" href="'. route('image.search',['tags_strings'=>$name]).'">'.  $images->count() .'</a> imagenes usando esta etiqueta (cambialas a la nueva)');
            }
        }
       
        

        
    }
}


//<a target="_blank" class="text-white" href="' . route('images.uniqHash', ['imagenHash' => $imagenHash]) . '">'