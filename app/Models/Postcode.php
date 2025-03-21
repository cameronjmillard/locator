<?php

namespace App\Models;

use Database\Factories\PostcodeFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Postcode extends Model
{
    /** @use HasFactory<PostcodeFactory> */
    use HasFactory;

    protected $fillable = ['postcode', 'latitude', 'longitude'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
