<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDesigner extends Model
{
    use HasFactory;

    protected $table = 'request_designers';

    protected $fillable = [
        'client_id',
        'designer_id',
        'purchased_design_id',
        'land_size',
        'land_shape',
        'sun_orientation',
        'wind_orientation',
        'design_reference_path',
        'budget',
        'notes',
        'deadline',
        'status',
        'progress',
    ];

    // Relasi ke user (client)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relasi ke user (designer)
    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    // Relasi ke purchased design
    public function purchasedDesign()
    {
        return $this->belongsTo(PurchasedDesign::class, 'purchased_design_id');
    }
}