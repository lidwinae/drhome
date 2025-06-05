<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message',
        'file_path'
    ];

    protected $appends = ['file_url'];

    /**
     * Get the URL for the chat file
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    /**
     * Relationship with sender
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship with recipient
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}