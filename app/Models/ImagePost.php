<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ImagePost extends Model
{
    use HasFactory;


    public function tags(){
        return $this->belongsToMany(Tag::class, 'tags_post', 'image_id', 'tag_id');
    }


    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'favorites_posts', 'image_id', 'user_id');
    }

    public function isFavoritedByUser(){
        return $this->favoritedBy->contains(Auth::user()->id);
    }
}
