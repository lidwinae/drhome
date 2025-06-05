<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;
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
