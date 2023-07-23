<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;
    
    public function urls(){
        return $this->hasMany(ArtistUrl::class);
    }

    public function tag(){
        return $this->belongsTo(Tag::class);
    }

    

}
