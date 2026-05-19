<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalTimesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_period_id',
        'working_days',
        'paid_leave_days',
        'unpaid_leave_days',
        'total_ot_minutes',
        'late_early_minutes',
    ];

    protected $casts = [
        'working_days' => 'decimal:2',
        'paid_leave_days' => 'decimal:1',
        'unpaid_leave_days' => 'decimal:1',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class);
    }
}
