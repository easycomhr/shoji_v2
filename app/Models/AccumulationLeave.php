<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccumulationLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'of_year',
        'accumulated_days',
    ];

    protected $casts = [
        'accumulated_days' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
