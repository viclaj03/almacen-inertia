<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'post_count', // Asegúrate de agregar este campo si no está presente
    ];

    public function artist(){
        return $this->hasOne(Artist::class);
    }
    
    public function model(){
        return $this->hasOne(Modelo::class);
    }



    public function imagePosts(){
        return $this->belongsToMany(ImagePost::class, 'tags_post', 'tag_id', 'image_id');
    }

    public function getImagePostCount(): int
    {
        return $this->imagePosts()->count();
    }

    public function getAllWithCount()
    {
        return $this->withCount('imagePosts');
    }

    public static function getCategoryForTag($tagName)
    {
        $tag = self::where('name', $tagName)->first();

        

        return $tag ? $tag->category : null;
    }




    public static function newTagGeneral($tagStringGeneneral){

        $tagsArray = explode(' ', $tagStringGeneneral);

        foreach ($tagsArray as $tagName) {
            $tagtagName = str_replace('_', ' ', $tagName);
            $tag = self::where('name', $tagtagName)->first();


        if(!$tag){
            $tag = new Tag();
            $tag->name = $tagtagName;
            $tag->translate_esp = $tagtagName;
            $tag->wiki =  '';
            $tag->category = 0;
            $tag->save();
        }

        }
    }


    public static function newTagCopyright($tagStringGeneneral){

        $tagsArray = explode(' ', $tagStringGeneneral);
        foreach ($tagsArray as $tagName) {
            $tagtagName = str_replace('_', ' ', $tagName);
            $tag = self::where('name', $tagtagName)->first();

            if($tagName == ' ' || $tagName == ''){
                return;
            }

        if(!$tag){
            $tag = new Tag();
            $tag->name = $tagtagName;
            $tag->translate_esp = $tagtagName;
            $tag->wiki =  '';
            $tag->category = 1;
            $tag->save();
        }

        }
    }



    public static function newTagCharacters($tagStringGeneneral){

        $tagsArray = explode(' ', $tagStringGeneneral);

        foreach ($tagsArray as $tagName) {
            $tagtagName = str_replace('_', ' ', $tagName);

            $tag = self::where('name', $tagtagName)->first();
            if($tagName == ' ' || $tagName == ''){
                return;
            }
        if(!$tag){
            $tag = new Tag();
            
            $tag->name = $tagtagName;
            $tag->translate_esp = $tagtagName;
            $tag->wiki =  '';
            $tag->category = 2;
            $tag->save();
        }

        }
    }



    public static function newTagMeta($tagStringGeneneral){

        $tagsArray = explode(' ', $tagStringGeneneral);

        foreach ($tagsArray as $tagName) {
            $tagtagName = str_replace('_', ' ', $tagName);
            $tag = self::where('name', $tagtagName)->first();

        if(!$tag){
            $tag = new Tag();
            $tag->name = $tagtagName;
            $tag->translate_esp = $tagtagName;
            $tag->wiki =  '';
            $tag->category = 4;
            $tag->save();
        }

        }
    }


    public static function orderAndCountTags($tagsString)
    {
        // Dividir el string en un array usando espacios como delimitador
        $tagsArray = explode(' ', $tagsString);

        $tagsArray = array_flip(array_flip($tagsArray));

        sort($tagsArray);
        
        // Iterar sobre cada elemento del array y reemplazar los guiones bajos con espacios
        foreach ($tagsArray as $tagName) {
            $tagString = str_replace('_', ' ', $tagName);
            $tag = self::where('name', $tagString)->first();

            if($tag){
            $tag->post_count +=1;
            $tag->save();
            }
        }
        //no va asi, separara

        
        return implode(' ', $tagsArray);
    }

    

}
