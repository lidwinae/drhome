<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedDesign extends Model
{
    use HasFactory;

    protected $table = 'purchased_designs';
    protected $fillable = [
        'user_id',
        'design_id',
        'design_path',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}