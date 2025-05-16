<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // 'role' dan 'status' dihapus dari sini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $attributes = [
        'role' => 'user',
        'status' => 'active',
    ];

    // Method khusus untuk update oleh admin
    public function updateByAdmin(array $attributes)
    {
        // Field yang boleh diupdate admin
        $allowed = ['role', 'status'];
        $filtered = array_intersect_key($attributes, array_flip($allowed));
        
        return $this->forceFill($filtered)->save();
    }

    public function designer()
    {
        return $this->hasOne(Designer::class, 'user_id');
    }
}