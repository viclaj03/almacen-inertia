<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePost extends Model
{
    use HasFactory;


    public function tags(){
        return $this->belongsToMany(Tag::class, 'tags_post', 'image_id', 'tag_id');
    }
}
