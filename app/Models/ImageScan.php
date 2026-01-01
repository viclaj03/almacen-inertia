<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'source_id',
        'file_url',
        'image_hash',
    ];

}
