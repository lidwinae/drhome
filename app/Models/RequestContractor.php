<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestContractor extends Model
{
    use HasFactory;
    protected $table = 'request_contractors';

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
    ];

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
