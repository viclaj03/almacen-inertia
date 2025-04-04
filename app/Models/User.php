<?php

namespace App\Models;

use BaconQrCode\Common\Mode;
use DragonCode\Contracts\Cashier\Resources\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function favoriteImages()
    {
        return $this->belongsToMany(ImagePost::class, 'favorites_posts', 'user_id', 'image_id');
    }


    public function favoriteArtists()
    {
        return $this->belongsToMany(Artist::class, 'favorites_artist', 'user_id', 'artist_id');
    }

    public function favoriteArtist(){
        return $this->belongsToMany(Artist::class, artist::class,'','');
    }



    public function favoriteModels()
    {
        return $this->belongsToMany(Modelo::class, 'favorite_model', 'user_id', 'models_id');
    }

    public function favoriteModel(){
        return $this->belongsToMany(Modelo::class, Modelo::class,'','');
    }


    public function getCommentForArtist($artistId)
    {
        return $this->favoriteArtists()
            ->where('artist_id', $artistId)
            ->select('comment', 'last_change_date')->first();
    }


    
}