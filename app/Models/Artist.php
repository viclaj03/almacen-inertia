<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Artist extends Model
{
    use HasFactory;
    
    public function urls_old(){
        return $this->hasMany(ArtistUrl::class);
    }

    public function tag(){
        return $this->belongsTo(Tag::class)->withCount('imagePosts');
    }

    public function tagImage(){
        return $this->belongsTo(Tag::class)->with('imagePosts');
    }
    
    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'favorites_artist', 'artist_id', 'user_id')->withPivot('comment','last_change_date');
    }

    public function isFavoritedByUser(){
        return $this->favoritedBy->contains(Auth::user()->id);
    }
        


}
