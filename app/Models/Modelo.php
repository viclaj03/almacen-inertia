<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Modelo extends Model
{
    use HasFactory;


    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'favorite_model', 'models_id', 'user_id')->withPivot('comment','last_change_date');
    }

    public function isFavoritedByUser(){
        return $this->favoritedBy->contains(Auth::user()->id);
    }
}
