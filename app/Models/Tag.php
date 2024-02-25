<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function artist(){
        return $this->hasOne(Artist::class);
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

    

}
