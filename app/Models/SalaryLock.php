<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLock extends Model
{
    use HasFactory;

    protected $fillable = ['salary_period_id', 'is_locked', 'locked_by', 'locked_at'];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    // === RELATIONSHIPS ===

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class);
    }
}
