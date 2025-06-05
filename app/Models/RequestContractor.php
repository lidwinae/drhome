<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestContractor extends Model
{
    use HasFactory;

    protected $table = 'request_contractors';

    // Penting untuk UUID
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'client_id',
        'contractor_id',
        'purchased_design_id',
        'province',
        'city',
        'land_size',
        'land_shape',
        'budget',
        'deadline',
        'status',
        'progress',
        'notes',
        'open_acc',
    ];

    // Auto-generate UUID saat membuat data
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function purchasedDesign()
    {
        return $this->belongsTo(PurchasedDesign::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
}
