<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    protected $fillable = [
        'id',
        'name',
        'country',
        'origin_city',
        'specialty',
        'photo',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
