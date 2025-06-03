<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false; // Karena user_id bukan auto increment
    protected $keyType = 'int';
    protected $fillable = [
        'user_id',
        'specialty',
        'description',
        'portfolio_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
