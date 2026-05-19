<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInsurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'social_insurance_number',
        'social_insurance_start_date',
        'social_insurance_place',
        'health_insurance_number',
        'health_insurance_place',
        'end_date',
        'is_locked',
    ];

    protected $casts = [
        'social_insurance_start_date' => 'date',
        'end_date' => 'date',
        'is_locked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
