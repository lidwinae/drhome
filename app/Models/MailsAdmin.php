<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailsAdmin extends Model
{
    protected $table = 'mails_admin';

    protected $fillable = [
        'user_id',
        'judul',
        'role',
        'message',
        'portfolio_path',
        'reply'
    ];

    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
