<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    protected $table = 'riwayat_email';
    protected $fillable = [
        'recipient', 
        'subject',
        'message',
        'status',
        'error',
        'user_id'
    ];
}
