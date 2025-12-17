<?php
namespace App\Traits;

/**
 * Trait cho các model có liên quan đến tính lương
 */
trait SalaryCalculationTrait
{
    /**
     * Tính lương theo tỷ lệ ngày làm việc
     */
    public function calculateProportionalSalary($baseSalary, $actualDays, $standardDays)
    {
        if ($standardDays <= 0) return 0;

        return round(($baseSalary / $standardDays) * $actualDays, 0);
    }

    /**
     * Tính tiền tăng ca
     */
    public function calculateOvertimeAmount($hourlyRate, $hours, $multiplier)
    {
        return round($hourlyRate * $hours * $multiplier, 0);
    }

    /**
     * Tính phần tăng ca không chịu thuế
     */
    public function calculateNonTaxableOvertime($overtimeAmount, $nonTaxRate)
    {
        return round($overtimeAmount * $nonTaxRate, 0);
    }

    /**
     * Kiểm tra điều kiện thưởng chuyên cần
     */
    public function checkPerfectAttendanceBonus($workingDays, $paidLeaveDays, $dayOff70Days, $lateHours, $unpaidLeaveDays, $isNewEmployee = false, $isResigned = false)
    {
        // Điều kiện: đủ ngày công, ít đi muộn, không nghỉ không phép, không phải nhân viên mới/nghỉ việc
        $totalDays = $workingDays + $paidLeaveDays + $dayOff70Days;

        return $totalDays > 0 &&
            $lateHours <= 2 &&
            $unpaidLeaveDays == 0 &&
            !$isNewEmployee &&
            !$isResigned;
    }
}