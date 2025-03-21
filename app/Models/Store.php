<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'store_type',
        'max_delivery_distance',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'max_delivery_distance' => 'integer',
    ];
}
