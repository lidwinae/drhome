<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestContractor extends Model
{
    protected $table = 'request_contractor';

    protected $fillable = [
        'client_id',
        'contractor_id', 
        'subject',
        'message',
        'status',
        'error',
        'user_id'
    ];
}
