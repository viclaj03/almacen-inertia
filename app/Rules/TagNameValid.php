<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TagNameValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        //$value = trim($value);



        if (strpos($value, '_') !== false) {
            $fail('Las etiquetas no pueden contener el carácter "_".');
            return;
        }


        $reserver_tags = [
            'fav'=>'favoritas del usuario',
            'order'=>'Para estableces un orden(no implenetado)',
            'order:'=>'Para estableces un orden(no implenetado)',
            'secondary url'=> 'para buscar si tienen o no 2º url(no implementado)',
            'secondary url:'=> 'para buscar si tienen o no 2º url(no implementado)',
            'tags_status'=>'para ver si tienen o no tags'
        ];


        if(array_key_exists($value,$reserver_tags)){
            $fail('Esta etiqueta ya está reservada para: ' . $reserver_tags[$value]);
        }

        
    }
}
