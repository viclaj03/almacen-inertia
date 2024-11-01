<?php

namespace App\Rules;

use App\Models\Artist;
use App\Models\Modelo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UrlNotDuplicate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        $urls = explode("\n",$value);

       

        foreach ($urls as $url) {
            // Limpiamos los espacios en blanco.
            $url = trim($url);

            // Buscamos si esta URL ya existe en algún artista.
            $artist = Artist::
            whereJsonContains('urls', $url)
            ->first();

            // Si encontramos un artista con una URL similar, fallamos la validación.
            if ($artist) {
                $fail("La URL '{$url}' ya está asociada al artista {$artist->name}.");
                return; // No necesitamos seguir validando más si ya encontramos una coincidencia.
            }
        }


    }
}
