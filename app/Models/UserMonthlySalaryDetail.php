<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMonthlySalaryDetail extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id', 'username', 'department', 'month', 'year',
        'basic_salary', 'position_allowance', 'evaluation_allowance', 'seniority_allowance',
        'adjustment_allowance', 'transportation_allowance', 'night_shift_allowance',
        'slippage_allowance', 'harmful_allowance', 'regular_allowance',
        'seniority_worker_allowance', 'skill_allowance', 'salary_allowance',
        'ot_day_hours', 'ot_night_hours', 'ot_dayoff_hours', 'ot_dayoff_night_hours',
        'ot_special_day_hours', 'ot_special_night_hours',
        'ot_day_amount', 'ot_night_amount', 'ot_dayoff_amount', 'ot_dayoff_night_amount',
        'ot_special_day_amount', 'ot_special_night_amount',
        'ot_day_non_tax_amount', 'ot_night_non_tax_amount', 'ot_dayoff_non_tax_amount',
        'ot_dayoff_night_non_tax_amount', 'ot_special_day_non_tax_amount', 'ot_special_night_non_tax_amount',
        'health_insurance', 'social_insurance', 'employment_insurance', 'union_fee',
        'late_deduction', 'leave_deduction',
        'non_taxable_allowance', 'non_taxable_deduction', 'taxable_allowance', 'taxable_deduction',
        'day_off_70_amount', 'day_off_fixed_amount', 'day_off_100_amount', 'perfect_attendance_bonus',
        'working_days', 'day_off_70_days', 'day_off_fixed_days', 'day_off_100_days',
        'paid_leave_days', 'unpaid_leave_days', 'late_hours', 'night_shift_hours',
        'payable_salary', 'taxable_income', 'pit_deduction', 'after_pit_deduction',
        'personal_income_tax', 'net_payment',
        'seniority_allowance_original', 'is_custom_day_off'
    ];

    protected $casts = [
        // Tất cả các trường tiền tệ
        'basic_salary' => 'decimal:2',
        'position_allowance' => 'decimal:2',
        'evaluation_allowance' => 'decimal:2',
        'seniority_allowance' => 'decimal:2',
        'adjustment_allowance' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'night_shift_allowance' => 'decimal:2',
        'slippage_allowance' => 'decimal:2',
        'harmful_allowance' => 'decimal:2',
        'regular_allowance' => 'decimal:2',
        'seniority_worker_allowance' => 'decimal:2',
        'skill_allowance' => 'decimal:2',
        'salary_allowance' => 'decimal:2',

        // Giờ tăng ca
        'ot_day_hours' => 'decimal:2',
        'ot_night_hours' => 'decimal:2',
        'ot_dayoff_hours' => 'decimal:2',
        'ot_dayoff_night_hours' => 'decimal:2',
        'ot_special_day_hours' => 'decimal:2',
        'ot_special_night_hours' => 'decimal:2',

        // Tiền tăng ca
        'ot_day_amount' => 'decimal:2',
        'ot_night_amount' => 'decimal:2',
        'ot_dayoff_amount' => 'decimal:2',
        'ot_dayoff_night_amount' => 'decimal:2',
        'ot_special_day_amount' => 'decimal:2',
        'ot_special_night_amount' => 'decimal:2',

        // Tiền tăng ca không chịu thuế
        'ot_day_non_tax_amount' => 'decimal:2',
        'ot_night_non_tax_amount' => 'decimal:2',
        'ot_dayoff_non_tax_amount' => 'decimal:2',
        'ot_dayoff_night_non_tax_amount' => 'decimal:2',
        'ot_special_day_non_tax_amount' => 'decimal:2',
        'ot_special_night_non_tax_amount' => 'decimal:2',

        // Bảo hiểm
        'health_insurance' => 'decimal:2',
        'social_insurance' => 'decimal:2',
        'employment_insurance' => 'decimal:2',
        'union_fee' => 'decimal:2',

        // Khấu trừ
        'late_deduction' => 'decimal:2',
        'leave_deduction' => 'decimal:2',
        'non_taxable_allowance' => 'decimal:2',
        'non_taxable_deduction' => 'decimal:2',
        'taxable_allowance' => 'decimal:2',
        'taxable_deduction' => 'decimal:2',

        // Các khoản đặc biệt
        'day_off_70_amount' => 'decimal:2',
        'day_off_fixed_amount' => 'decimal:2',
        'day_off_100_amount' => 'decimal:2',
        'perfect_attendance_bonus' => 'decimal:2',

        // Ngày công và giờ
        'working_days' => 'decimal:2',
        'day_off_70_days' => 'decimal:2',
        'day_off_fixed_days' => 'decimal:2',
        'day_off_100_days' => 'decimal:2',
        'paid_leave_days' => 'decimal:2',
        'unpaid_leave_days' => 'decimal:2',
        'late_hours' => 'decimal:2',
        'night_shift_hours' => 'decimal:2',

        // Tính toán lương
        'payable_salary' => 'decimal:2',
        'taxable_income' => 'decimal:2',
        'pit_deduction' => 'decimal:2',
        'after_pit_deduction' => 'decimal:2',
        'personal_income_tax' => 'decimal:2',
        'net_payment' => 'decimal:2',
        'seniority_allowance_original' => 'decimal:2',

        // Boolean
        'is_custom_day_off' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeHighSalary($query, $amount = 10000000)
    {
        return $query->where('net_payment', '>=', $amount);
    }

    // === ACCESSORS ===

    /**
     * Tổng phụ cấp cơ bản
     */
    public function getTotalBasicAllowancesAttribute()
    {
        return $this->position_allowance +
            $this->evaluation_allowance +
            $this->seniority_allowance +
            $this->adjustment_allowance +
            $this->slippage_allowance +
            $this->harmful_allowance +
            $this->regular_allowance +
            $this->skill_allowance;
    }

    /**
     * Tổng giờ tăng ca
     */
    public function getTotalOvertimeHoursAttribute()
    {
        return $this->ot_day_hours +
            $this->ot_night_hours +
            $this->ot_dayoff_hours +
            $this->ot_dayoff_night_hours +
            $this->ot_special_day_hours +
            $this->ot_special_night_hours;
    }

    /**
     * Tổng tiền tăng ca (chịu thuế)
     */
    public function getTotalOvertimeAmountAttribute()
    {
        return $this->ot_day_amount +
            $this->ot_night_amount +
            $this->ot_dayoff_amount +
            $this->ot_dayoff_night_amount +
            $this->ot_special_day_amount +
            $this->ot_special_night_amount;
    }

    /**
     * Tổng tiền tăng ca không chịu thuế
     */
    public function getTotalOvertimeNonTaxAmountAttribute()
    {
        return $this->ot_day_non_tax_amount +
            $this->ot_night_non_tax_amount +
            $this->ot_dayoff_non_tax_amount +
            $this->ot_dayoff_night_non_tax_amount +
            $this->ot_special_day_non_tax_amount +
            $this->ot_special_night_non_tax_amount;
    }

    /**
     * Tổng bảo hiểm
     */
    public function getTotalInsuranceAttribute()
    {
        return $this->health_insurance + $this->social_insurance + $this->employment_insurance;
    }

    /**
     * Tổng ngày nghỉ
     */
    public function getTotalLeaveDaysAttribute()
    {
        return $this->day_off_70_days +
            $this->day_off_fixed_days +
            $this->day_off_100_days +
            $this->paid_leave_days +
            $this->unpaid_leave_days;
    }

    /**
     * Lương gross (trước thuế)
     */
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary +
            $this->total_basic_allowances +
            $this->transportation_allowance +
            $this->night_shift_allowance +
            $this->seniority_worker_allowance +
            $this->salary_allowance +
            $this->total_overtime_amount +
            $this->perfect_attendance_bonus +
            $this->day_off_70_amount +
            $this->day_off_fixed_amount +
            $this->day_off_100_amount;
    }

    // === METHODS ===

    /**
     * Tính lại thuế thu nhập cá nhân
     */
    public function recalculateTax($dependents = 0)
    {
        $selfDeduction = 11000000; // 11 triệu cho bản thân
        $dependentDeduction = 4400000; // 4.4 triệu cho mỗi người phụ thuộc

        $totalDeduction = $selfDeduction + ($dependents * $dependentDeduction);
        $taxableAmount = max(0, $this->taxable_income - $totalDeduction);

        // Bảng thuế lũy tiến từng phần
        $taxBrackets = [
            [0, 5000000, 0.05],
            [5000000, 10000000, 0.10],
            [10000000, 18000000, 0.15],
            [18000000, 32000000, 0.20],
            [32000000, 52000000, 0.25],
            [52000000, 80000000, 0.30],
            [80000000, PHP_INT_MAX, 0.35],
        ];

        $tax = 0;
        $remaining = $taxableAmount;

        foreach ($taxBrackets as [$min, $max, $rate]) {
            if ($remaining <= 0) break;

            $taxableInBracket = min($remaining, $max - $min);
            $tax += $taxableInBracket * $rate;
            $remaining -= $taxableInBracket;
        }

        return round($tax);
    }

    /**
     * Export sang array để xuất Excel
     */
    public function toExportArray()
    {
        return [
            'Mã NV' => $this->user->employee_code ?? '',
            'Họ tên' => $this->username,
            'Phòng ban' => $this->department,
            'Lương cơ bản' => number_format($this->basic_salary),
            'Phụ cấp chức vụ' => number_format($this->position_allowance),
            'Phụ cấp đánh giá' => number_format($this->evaluation_allowance),
            'Phụ cấp thâm niên' => number_format($this->seniority_allowance),
            'Phụ cấp đi lại' => number_format($this->transportation_allowance),
            'Tiền tăng ca' => number_format($this->total_overtime_amount),
            'BHXH' => number_format($this->social_insurance),
            'BHYT' => number_format($this->health_insurance),
            'BHTN' => number_format($this->employment_insurance),
            'Thuế TNCN' => number_format($this->personal_income_tax),
            'Thực lãnh' => number_format($this->net_payment),
        ];
    }
}
