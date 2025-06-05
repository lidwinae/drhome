<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestDesigner extends Model
{
    use HasFactory;

    protected $table = 'request_designers';

    // Konfigurasi UUID
    public $incrementing = false;
    protected $keyType = 'string';

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
        'open_acc'
    ];

    // Otomatis isi UUID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

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
