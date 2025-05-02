<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    protected $fillable = [
        'name', 
        'country',
        'origin_city',
        'specialty',
        'photo',
        'description'
    ];
}