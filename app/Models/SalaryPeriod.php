<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'from_date', 'to_date', 'standard_working_days', 'is_locked'
    ];

    protected $casts = [
//        'from_date' => 'date',
//        'to_date' => 'date',
        'is_locked' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function salaryLock()
    {
        return $this->hasOne(SalaryLock::class);
    }

    // === SCOPES ===

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('from_date', '<=', $now)
            ->where('to_date', '>=', $now);
    }

    // === METHODS ===

    public function lock($userId = null)
    {
        $this->update(['is_locked' => true]);

        SalaryLock::updateOrCreate(
            ['salary_period_id' => $this->id],
            [
                'is_locked' => true,
                'locked_by' => $userId,
                'locked_at' => now()
            ]
        );
    }

    public function unlock()
    {
        $this->update(['is_locked' => false]);
        $this->salaryLock()?->update(['is_locked' => false]);
    }
}
