<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'applied_date', 'salary', 'position_allowance',
        'evaluation_allowance', 'harmfulness_allowance', 'skill_allowance',
        'seniority_allowance', 'adjustment_allowance', 'slippage_allowance',
        'regular_allowance', 'union_fee', 'health_insurance_included',
        'social_insurance_included'
    ];

    protected $casts = [
        'applied_date' => 'date',
        'salary' => 'decimal:2',
        'position_allowance' => 'decimal:2',
        'evaluation_allowance' => 'decimal:2',
        'harmfulness_allowance' => 'decimal:2',
        'skill_allowance' => 'decimal:2',
        'seniority_allowance' => 'decimal:2',
        'adjustment_allowance' => 'decimal:2',
        'slippage_allowance' => 'decimal:2',
        'regular_allowance' => 'decimal:2',
        'union_fee' => 'decimal:2',
        'health_insurance_included' => 'boolean',
        'social_insurance_included' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForPeriod($query, $date)
    {
        return $query->where('applied_date', '<=', $date)
            ->orderBy('applied_date', 'desc');
    }

    // === ACCESSORS ===

    /**
     * Tổng lương cơ bản + phụ cấp
     */
    public function getTotalBaseSalaryAttribute()
    {
        return $this->salary +
            $this->position_allowance +
            $this->evaluation_allowance +
            $this->harmfulness_allowance +
            $this->skill_allowance +
            $this->seniority_allowance +
            $this->adjustment_allowance +
            $this->slippage_allowance +
            $this->regular_allowance;
    }

    /**
     * Lương dùng để tính bảo hiểm
     */
    public function getInsuranceSalaryAttribute()
    {
        return $this->total_base_salary;
    }
}
