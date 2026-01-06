<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'from_date', 'to_date', 'standard_working_days', 'is_locked'
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];
}
