<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'description',
        'portfolio',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
