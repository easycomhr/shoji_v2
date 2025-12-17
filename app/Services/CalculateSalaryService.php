<?php

namespace App\Services;
// =============================================================================
// SERVICE - Xử lý logic tính lương phức tạp
// =============================================================================

namespace App\Services;

use App\Models\{
    User, SalaryPeriod, SalaryHistory, UserLeave, UserOvertime,
    UserMonthlySalaryDetail, ShiftKey, WorkShift, UserWorkHour,
    TransportationAllowance, SystemParameter, Holiday, UserCustom,
    UserPersonalIncomeTaxDeduction, TaxAllowanceDeduction,
    NonTaxAllowanceDeduction, SalaryAllowance, ShiftDurationCheck,
    Group, GroupHistory, EmployeePosition, EmployeeStatus
};
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * SalaryCalculationService - Service tính lương chính
 *
 * Chứa toàn bộ logic tính lương phức tạp theo đúng quy trình:
 * 1. Tính ngày công thực tế
 * 2. Tính các loại nghỉ phép
 * 3. Tính tăng ca (6 loại)
 * 4. Tính ca đêm và phụ cấp
 * 5. Tính lương cơ bản và phụ cấp
 * 6. Tính bảo hiểm
 * 7. Tính thuế thu nhập cá nhân
 * 8. Tính lương thực lãnh
 */
class CalculateSalaryService
{
    // Các hằng số tính lương
    const WORKING_IN_JAPAN_STATUS = 8;
    const TEMPORARY_WORK_STATUS = 11;
    const RESIGNED_STATUS = 4;
    const PROBATION_STATUS = 12;
    const INACTIVE_STATUS = 9;

    // Hệ số tăng ca
    const DAY_OT_RATE = 1.5;
    const NIGHT_OT_RATE = 1.5;
    const DAYOFF_OT_RATE = 2.0;
    const SPECIAL_OT_RATE = 3.0;

    // Tỷ lệ bảo hiểm
    const HEALTH_INSURANCE_RATE = 0.015;      // 1.5%
    const SOCIAL_INSURANCE_RATE = 0.08;       // 8%
    const EMPLOYMENT_INSURANCE_RATE = 0.01;   // 1%

    // Giới hạn bảo hiểm
    const HEALTH_INSURANCE_MAX = 46800000;    // 46.8 triệu
    const SOCIAL_INSURANCE_MAX = 46800000;    // 46.8 triệu
    const EMPLOYMENT_INSURANCE_MAX = 99200000; // 99.2 triệu
    const INSURANCE_MIN = 2300000;            // 2.3 triệu

    // Thuế thu nhập cá nhân
    const SELF_DEDUCTION = 11000000;          // 11 triệu cho bản thân
    const DEPENDENT_DEDUCTION = 4400000;      // 4.4 triệu cho người phụ thuộc

    // Thưởng chuyên cần
    const PERFECT_ATTENDANCE_BONUS = 300000;  // 300k

    // Lương tối thiểu cố định
    const MIN_SALARY_FIXED = 4420000;         // 4.42 triệu

    /**
     * Hàm chính tính lương cho nhân viên
     *
     * @param array $params - Tham số tính lương
     * @return int - Số lượng nhân viên đã tính
     */
    public function calculateSalary(array $params): int
    {
        // 1. Lấy thông tin kỳ lương
        $salaryPeriod = SalaryPeriod::findOrFail($params['salary_period_id']);
        $fromDate = Carbon::parse($salaryPeriod->from_date);
        $toDate = Carbon::parse($salaryPeriod->to_date);
        $month = $toDate->month;
        $year = $toDate->year;
        $standardWorkingDays = $salaryPeriod->standard_working_days;

        // 2. Lấy tham số hệ thống
        $systemParams = $this->getSystemParameters();

        // 3. Lấy danh sách nhân viên cần tính
        $employees = $this->getEmployeesToCalculate($params, $fromDate, $toDate);

        // 4. Lấy dữ liệu hỗ trợ
        $supportData = $this->getSupportData($fromDate, $toDate, $month, $year);
        $totalCalculated = 0;

        // 5. Tính lương từng nhân viên
        foreach ($employees as $employee) {
            try {
                $result = $this->calculateEmployeeSalary(
                    $employee,
                    $fromDate,
                    $toDate,
                    $month,
                    $year,
                    $standardWorkingDays,
                    $systemParams,
                    $supportData
                );

                if ($result && $result['net_payment'] > 0) {
                    $this->saveSalaryResult($employee->id, $month, $year, $result);
                    $totalCalculated++;
                } else {
                    // Xóa bản ghi nếu lương <= 0
                    $this->deleteSalaryResult($employee->id, $month, $year);
                }

            } catch (\Exception $e) {

                dd($e->getTraceAsString());
                \Log::error("Lỗi tính lương nhân viên {$employee->id}: " . $e->getMessage());
                continue;
            }
        }

        return $totalCalculated;
    }

    /**
     * Lấy tham số hệ thống
     *
     * @return array
     */
    private function getSystemParameters(): array
    {
        return [
            'day_calc_work_off' => SystemParameter::getValue('DAY_PER_MONTH_TO_CALC_WORK_OFF', 26)
        ];
    }

    /**
     * Lấy danh sách nhân viên cần tính lương
     *
     * @param array $params
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return Collection
     */
    private function getEmployeesToCalculate(array $params, Carbon $fromDate, Carbon $toDate): Collection
    {
        $query = User::with([
            'department',
            'salaryHistories',
            'groupHistories.group',
            'employeePositions',
            'employeeStatuses',
            'personalIncomeTaxDeductions',
            'transportationAllowance',
            'salaryAllowances' => fn($q) => $q->forPeriod($fromDate->month, $fromDate->year),
            'taxAllowanceDeductions' => fn($q) => $q->forPeriod($fromDate->month, $fromDate->year),
            'nonTaxAllowanceDeductions' => fn($q) => $q->forPeriod($fromDate->month, $fromDate->year)
        ]);

        // Lọc theo chế độ tính lương
        switch ($params['calculate_for']) {
            case 'EmployeeID':
                $query->where('id', $params['employee_id']);
                break;

            case 'Department':
                $query->where('department_id', $params['department_id']);
                break;

            case 'All':
                break;
        }

        // Lọc nhân viên đủ điều kiện
        $query->where(function ($q) use ($fromDate, $toDate) {
            $q->whereHas('employeeStatuses', function ($sq) use ($fromDate, $toDate) {
                $sq->whereNotIn('status_id', [self::RESIGNED_STATUS, self::INACTIVE_STATUS])
                    ->orWhere(function ($ssq) use ($fromDate, $toDate) {
                        $ssq->where('status_id', self::RESIGNED_STATUS)
                            ->whereBetween('applied_date', [$fromDate, $toDate]);
                    });
            });
        })->where('probation_start', '<=', $toDate);

        return $query->get();
    }

    /**
     * Lấy dữ liệu hỗ trợ tính lương
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @param int $month
     * @param int $year
     * @return array
     */
    private function getSupportData(Carbon $fromDate, Carbon $toDate, int $month, int $year): array
    {
        return [
            // Danh sách ngày lễ
            'holidays' => Holiday::forPeriod($fromDate, $toDate)->pluck('holiday_date')->toArray(),

            // Phụ cấp đi lại
            'transportation_allowances' => $this->getTransportationAllowances($toDate),

            // Ca làm việc
            'night_shifts' => WorkShift::nightShift()->pluck('id')->toArray(),
            'day_off_shifts' => WorkShift::dayOff()->pluck('id')->toArray(),

            // Hệ số ca đêm đặc biệt
            'night_shift_hours' => [
                312 => 6.5, 612 => 7.5, 512 => 8, 912 => 8, 3122 => 4,
                3 => 8, 52 => 5.5, 75 => 6, 743 => 6, 712 => 7, 3123 => 5, 412 => 8
            ]
        ];
    }

    /**
     * Lấy phụ cấp đi lại theo thời điểm
     *
     * @param Carbon $toDate
     * @return array
     */
    private function getTransportationAllowances(Carbon $toDate): array
    {
        $allowances = TransportationAllowance::forDate($toDate)
            ->limit(10)
            ->get();

        $result = [];
        foreach ($allowances as $allowance) {
            $result[$allowance->id] = $allowance->allowance;
        }

        return $result;
    }

    /**
     * Tính lương cho một nhân viên cụ thể
     *
     * @param User $employee
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @param int $month
     * @param int $year
     * @param int $standardWorkingDays
     * @param array $systemParams
     * @param array $supportData
     * @return array|null
     */
    private function calculateEmployeeSalary(
        User   $employee,
        Carbon $fromDate,
        Carbon $toDate,
        int    $month,
        int    $year,
        int    $standardWorkingDays,
        array  $systemParams,
        array  $supportData
    ): ?array
    {

        // 1. Lấy thông tin cơ bản của nhân viên
        $employeeInfo = $this->getEmployeeBasicInfo($employee, $fromDate, $toDate);

        // 2. Tính ngày công thực tế
        $workingDays = $this->calculateWorkingDays($employee, $month, $year, $supportData);

        // 3. Tính các loại nghỉ phép
        $leaveData = $this->calculateLeaves($employee, $fromDate, $toDate);

        // 4. Tính tăng ca
        $overtimeData = $this->calculateOvertimes($employee, $fromDate, $toDate);

        // 5. Tính ca đêm
        $nightShiftData = $this->calculateNightShift($employee, $month, $year, $supportData);

        // 6. Lấy lương cơ bản và phụ cấp
        $salaryData = $this->getSalaryAndAllowances($employee, $fromDate, $toDate, $month, $year);

        // 7. Tính lương bình quân nếu có thay đổi
        $averageSalary = $this->calculateAverageSalary($salaryData, $workingDays, $fromDate, $toDate);

        // 8. Tính các khoản phụ cấp
        $allowances = $this->calculateAllowances($employee, $employeeInfo, $salaryData, $workingDays, $leaveData, $supportData);

        // 9. Tính lương gross (trước thuế)
        $grossSalary = $this->calculateGrossSalary($averageSalary, $allowances, $overtimeData, $nightShiftData, $workingDays, $leaveData);

        // 10. Tính bảo hiểm
        $insurance = $this->calculateInsurance($employee, $salaryData, $workingDays, $leaveData);

        // 11. Tính thuế thu nhập cá nhân
        $taxData = $this->calculatePersonalIncomeTax($employee, $grossSalary, $insurance);

        // 12. Tính lương thực lãnh
        $netSalary = $this->calculateNetSalary($grossSalary, $insurance, $taxData, $nightShiftData, $salaryData);

        // 13. Kiểm tra điều kiện trả lương
        if (!$this->shouldPaySalary($employee, $netSalary, $workingDays, $fromDate, $toDate)) {
            return null;
        }

        // 14. Trả về kết quả tính lương
        return $this->formatSalaryResult($employee, $employeeInfo, $workingDays, $leaveData, $overtimeData, $nightShiftData, $allowances, $insurance, $taxData, $grossSalary, $netSalary, $month, $year);
    }

    /**
     * Lấy thông tin cơ bản của nhân viên
     *
     * @param User $employee
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    private function getEmployeeBasicInfo(User $employee, Carbon $fromDate, Carbon $toDate): array
    {
        // Lấy nhóm làm việc hiện tại
        $currentGroup = $employee->groupHistories()
            ->forDate($toDate)
            ->with('group')
            ->first();

        // Lấy chức vụ hiện tại
        $currentPosition = $employee->employeePositions()
            ->forDate($toDate)
            ->first();

        // Lấy trạng thái hiện tại
        $currentStatus = $employee->employeeStatuses()
            ->forDate($toDate)
            ->first();

        // Tính số tháng làm việc (để tính phụ cấp thâm niên)
        $monthsWorked = $employee->probation_start ?
            $employee->probation_start->diffInMonths($toDate) : 0;

        // Lấy số người phụ thuộc
        $dependents = $employee->personalIncomeTaxDeductions()
            ->forDate($toDate)
            ->first();

        // Lấy dữ liệu tùy chỉnh
        $customData = [];
//        foreach ($employee->userCustoms as $custom) {
//            $customData[$custom->key] = $custom->value_int ?? $custom->value_text ?? $custom->value_decimal;
//        }

        return [
            'working_hours_per_day' => $currentGroup?->group?->working_hours_per_day ?? 9.5,
            'working_days_per_month' => $currentGroup?->group?->working_days_per_month ?? 20,
            'company_position_id' => $currentPosition?->company_position_id,
            'user_status_id' => $currentStatus?->status_id ?? 1,
            'user_status_from_date' => $currentStatus?->applied_date,
            'months_worked' => $monthsWorked,
            'dependents_count' => $dependents?->number_of_dependents ?? 0,
            'custom_data' => $customData,
            'transportation_id' => $employee->transportation_id
        ];
    }

    /**
     * Tính ngày công thực tế trong tháng
     *
     * @param User $employee
     * @param int $month
     * @param int $year
     * @param array $supportData
     * @return array
     */
    private function calculateWorkingDays(User $employee, int $month, int $year, array $supportData): array
    {
        // Lấy lịch ca làm việc của nhân viên
        $shiftKey = $employee->shiftKeys()
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if (!$shiftKey) {
            return [
                'total_scheduled_days' => 0,
                'working_days' => 0,
                'day_off_days' => 0,
                'shift_schedule' => []
            ];
        }

        $workingDays = 0;
        $dayOffDays = 0;
        $shiftSchedule = [];

        // Duyệt qua 31 ngày trong tháng
        for ($day = 1; $day <= 31; $day++) {
            $dayColumn = 'd' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $shiftId = $shiftKey->$dayColumn;

            if ($shiftId > 0) {
                // Kiểm tra ca này có phải ca nghỉ không
                if (in_array($shiftId, $supportData['day_off_shifts'])) {
                    $dayOffDays++;
                    $shiftSchedule[$day] = 'day_off';
                } else {
                    $workingDays++;
                    $shiftSchedule[$day] = 'working';

                    // Kiểm tra ca đêm
                    if (in_array($shiftId, $supportData['night_shifts'])) {
                        $shiftSchedule[$day] = 'night_shift';
                    }
                }
            } else {
                $shiftSchedule[$day] = 'no_shift';
            }
        }

        return [
            'total_scheduled_days' => $workingDays + $dayOffDays,
            'working_days' => $workingDays,
            'day_off_days' => $dayOffDays,
            'shift_schedule' => $shiftSchedule
        ];
    }

    /**
     * Tính các loại nghỉ phép
     *
     * @param User $employee
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    private function calculateLeaves(User $employee, Carbon $fromDate, Carbon $toDate): array
    {
        // Nghỉ 70% lương (nghỉ ốm) - leavetypeid = 6, 24
        $dayOff70Hours = $employee->userLeaves()
            ->byType([6, 24])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->sum('leave_amount');

        // Nghỉ lương cố định - leavetypeid = 21
        $dayOffFixedHours = $employee->userLeaves()
            ->byType([21])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->sum('leave_amount');

        // Nghỉ 100% lương - leavetypeid = 22, 23
        $dayOff100Hours = $employee->userLeaves()
            ->byType([22, 23])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->sum('leave_amount');

        // Nghỉ phép có lương - leavetypeid = 3,5,7,8,9,10,13,16,18,19,22,23
        $paidLeaveHours = $employee->userLeaves()
            ->byType([3, 5, 7, 8, 9, 10, 13, 16, 18, 19, 22, 23])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->sum('leave_amount');

        // Nghỉ không lương - leavetypeid = 1,2,4,11,12,17,20,21,25
        $unpaidLeaveHours = $employee->userLeaves()
            ->byType([1, 2, 4, 11, 12, 17, 20, 21, 25])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->sum('leave_amount');

        // Nghỉ thai sản - leavetypeid = 17
        $maternityLeave = $employee->userLeaves()
            ->byType([17])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->count();

        // Nghỉ không lương khác - leavetypeid = 5
        $otherUnpaidLeave = $employee->userLeaves()
            ->byType([5])
            ->forPeriod($fromDate, $toDate)
            ->approved()
            ->count();

        return [
            'day_off_70_hours' => $dayOff70Hours,
            'day_off_70_days' => round($dayOff70Hours / 8, 2),
            'day_off_fixed_hours' => $dayOffFixedHours,
            'day_off_fixed_days' => round($dayOffFixedHours / 8, 2),
            'day_off_100_hours' => $dayOff100Hours,
            'day_off_100_days' => round($dayOff100Hours / 8, 2),
            'paid_leave_hours' => $paidLeaveHours,
            'paid_leave_days' => round($paidLeaveHours / 8, 2),
            'unpaid_leave_hours' => $unpaidLeaveHours,
            'unpaid_leave_days' => round($unpaidLeaveHours / 8, 2),
            'maternity_leave_days' => $maternityLeave,
            'other_unpaid_leave_days' => $otherUnpaidLeave
        ];
    }

    /**
     * Tính tăng ca (6 loại)
     *
     * @param User $employee
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    private function calculateOvertimes(User $employee, Carbon $fromDate, Carbon $toDate): array
    {
        // OT1 - Tăng ca ngày thường (hệ số 1.5)
        $otDayHours = $this->getOvertimeByType($employee, 1, $fromDate, $toDate);

        // OT2 - Tăng ca đêm (hệ số 1.5)
        $otNightHours = $this->getOvertimeByType($employee, 2, $fromDate, $toDate);

        // OT3 - Tăng ca ngày nghỉ (hệ số 2.0)
        $otDayOffHours = $this->getOvertimeByType($employee, 3, $fromDate, $toDate);

        // OT5 - Tăng ca đặc biệt ngày (hệ số 3.0)
        $otSpecialDayHours = $this->getOvertimeByType($employee, 5, $fromDate, $toDate);

        // OT7 - Tăng ca đêm ngày nghỉ (hệ số 2.0)
        $otDayOffNightHours = $this->getOvertimeByType($employee, 7, $fromDate, $toDate);

        // OT8 - Tăng ca đêm đặc biệt (hệ số 3.0)
        $otSpecialNightHours = $this->getOvertimeByType($employee, 8, $fromDate, $toDate);

        return [
            'ot_day_hours' => $otDayHours,
            'ot_night_hours' => $otNightHours,
            'ot_dayoff_hours' => $otDayOffHours,
            'ot_special_day_hours' => $otSpecialDayHours,
            'ot_dayoff_night_hours' => $otDayOffNightHours,
            'ot_special_night_hours' => $otSpecialNightHours
        ];
    }

    /**
     * Lấy số giờ tăng ca theo loại
     *
     * @param User $employee
     * @param int $overtimeTypeId
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return float
     */
    private function getOvertimeByType(User $employee, int $overtimeTypeId, Carbon $fromDate, Carbon $toDate): float
    {
        return $employee->userOvertimes()
            ->byType($overtimeTypeId)
            ->forPeriod($fromDate, $toDate)
            ->approved()
//            ->sum('effective_hours'); // Sử dụng accessor effective_hours
            ->sum('modified_hours'); // Sử dụng accessor effective_hours
    }

    /**
     * Tính ca đêm và phụ cấp ca đêm
     *
     * @param User $employee
     * @param int $month
     * @param int $year
     * @param array $supportData
     * @return array
     */
    private function calculateNightShift(User $employee, int $month, int $year, array $supportData): array
    {
        $totalNightHours = 0;
        $totalNightDays = 0;

        // Lấy lịch ca làm việc
        $shiftKey = $employee->shiftKeys()
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if (!$shiftKey) {

            return [
                'night_shift_hours' => 0,
                'night_shift_days' => 0,
                'night_shift_allowance_rate' => 0.3,
                'night_shift_allowance' => 0,
            ];
        }

        // Lấy thông tin nhóm làm việc để biết số giờ/ngày
        $workingHoursPerDay = 9.5; // Mặc định
        $currentGroup = $employee->groupHistories()
            ->whereNull('to_date')
            ->with('group')
            ->first();

        if ($currentGroup && $currentGroup->group) {
            $workingHoursPerDay = $currentGroup->group->working_hours_per_day;
        }

        // Duyệt qua từng ngày trong tháng
        for ($day = 1; $day <= 31; $day++) {
            $dayColumn = 'd' . str_pad($day, 2, '0', STR_PAD_LEFT);
            $shiftId = $shiftKey->$dayColumn;

            // Kiểm tra có phải ca đêm không
            if ($shiftId && in_array($shiftId, $supportData['night_shifts'])) {
                $dateWorked = Carbon::create($year, $month, $day);

                // Kiểm tra nhân viên có nghỉ phép ngày này không
                $hasLeave = $employee->userLeaves()
                    ->where('leave_date', $dateWorked)
                    ->approved()
                    ->exists();

                if (!$hasLeave) {
                    // Lấy giờ làm việc thực tế
                    $workHour = $employee->userWorkHours()
                        ->where('date_worked', $dateWorked)
                        ->first();

                    if ($workHour) {
                        // Tính giờ ca đêm (22h-6h sáng hôm sau)
                        $nightHours = $this->calculateNightShiftHours($workHour, $shiftId, $supportData['night_shift_hours']);

                        $totalNightHours += $nightHours;

                        // Tính số ngày ca đêm tương ứng
                        if (isset($supportData['night_shift_hours'][$shiftId])) {
                            $standardNightHours = $supportData['night_shift_hours'][$shiftId];
                            $totalNightDays += $nightHours / $standardNightHours;
                        } else {
                            $totalNightDays += $nightHours / $workingHoursPerDay;
                        }
                    }
                }
            }
        }

        // Xác định hệ số phụ cấp ca đêm (0.3 hoặc 0.35)
        $nightShiftRate = ($totalNightDays >= 8) ? 0.35 : 0.3;

        // Thêm tính toán tiền phụ cấp ca đêm
        $nightShiftAllowance = 0;
        if ($totalNightDays > 0) {
            // Lấy lương ngày cơ bản để tính phụ cấp
            $latestSalary = $employee->salaryHistories()
                ->orderBy('applied_date', 'desc')
                ->first();

            if ($latestSalary) {
                $dailyRate = $latestSalary->salary / 26; // 26 ngày chuẩn
                $nightShiftAllowance = round($totalNightDays * $nightShiftRate * $dailyRate, 0);
            }
        }

        return [
            'night_shift_hours' => round($totalNightHours, 2),
            'night_shift_days' => round($totalNightDays, 2),
            'night_shift_allowance_rate' => $nightShiftRate,
            'night_shift_allowance' => $nightShiftAllowance
        ];
    }

    /**
     * Tính giờ ca đêm thực tế (22h-6h)
     *
     * @param UserWorkHour $workHour
     * @param int $shiftId
     * @param array $nightShiftHours
     * @return float
     */
    private function calculateNightShiftHours(UserWorkHour $workHour, int $shiftId, array $nightShiftHours): float
    {
        $workStart = $workHour->from_datetime;
        $workEnd = $workHour->to_datetime;

        // Khung giờ ca đêm: 22h hôm nay đến 6h sáng hôm sau
        $nightStart = $workStart->copy()->setTime(22, 0, 0);
        $nightEnd = $workStart->copy()->addDay()->setTime(6, 0, 0);

        // Tính giao điểm giữa giờ làm việc và khung giờ ca đêm
        $actualNightStart = max($workStart, $nightStart);
        $actualNightEnd = min($workEnd, $nightEnd);

        if ($actualNightStart >= $actualNightEnd) {
            return 0; // Không có giờ ca đêm
        }

        $nightHours = $actualNightStart->diffInMinutes($actualNightEnd) / 60;

        // Làm tròn theo quy tắc (phải >= 30 phút mới tính)
        if ($nightHours < 0.5) {
            return 0;
        }

        // Giới hạn theo loại ca
        if (isset($nightShiftHours[$shiftId])) {
            $nightHours = min($nightHours, $nightShiftHours[$shiftId]);
        }

        return round($nightHours, 2);
    }

    /**
     * Lấy lương cơ bản và các phụ cấp
     *
     * @param User $employee
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @param int $month
     * @param int $year
     * @return array
     */
    private function getSalaryAndAllowances(User $employee, Carbon $fromDate, Carbon $toDate, int $month, int $year): array
    {

        // Lấy lương gần nhất
        $latestSalary = $employee->salaryHistories()
            ->forPeriod($toDate)
            ->first();

        if (!$latestSalary) {
            throw new \Exception("Không tìm thấy thông tin lương cho nhân viên {$employee->id}");
        }

        // Lấy phụ cấp lương tháng này (nếu có)
        $salaryAllowance = $employee->salaryAllowances()
            ->forPeriod($month, $year)
            ->first();

        // Lấy phụ cấp/khấu trừ chịu thuế
        $taxAllowanceDeduction = $employee->taxAllowanceDeductions()
            ->forPeriod($month, $year)
            ->first();

        // Lấy phụ cấp/khấu trừ không chịu thuế
        $nonTaxAllowanceDeduction = $employee->nonTaxAllowanceDeductions()
            ->forPeriod($month, $year)
            ->first();

        return [
            'salary_history' => $latestSalary,
            'salary_allowance' => $salaryAllowance?->allowance ?? 0,
            'tax_allowance' => $taxAllowanceDeduction?->allowance ?? 0,
            'tax_deduction' => $taxAllowanceDeduction?->deduction ?? 0,
            'non_tax_allowance' => $nonTaxAllowanceDeduction?->allowance ?? 0,
            'non_tax_deduction' => $nonTaxAllowanceDeduction?->deduction ?? 0
        ];
    }

    /**
     * Tính lương bình quân khi có thay đổi lương giữa tháng
     *
     * @param array $salaryData
     * @param array $workingDays
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return array
     */
    private function calculateAverageSalary(array $salaryData, array $workingDays, Carbon $fromDate, Carbon $toDate): array
    {
        $salaryHistory = $salaryData['salary_history'];
        $appliedDate = $salaryHistory->applied_date;

        // Kiểm tra có thay đổi lương giữa tháng không
        if ($appliedDate->between($fromDate, $toDate) && !$appliedDate->eq($fromDate)) {
            // Lấy mức lương cũ
            $previousSalary = $salaryHistory->user->salaryHistories()
                ->where('applied_date', '<', $appliedDate)
                ->orderBy('applied_date', 'desc')
                ->first();

            if ($previousSalary) {
                // Tính số ngày áp dụng mức lương mới và cũ
                $dayInMonth = $appliedDate->day;
                $totalWorkingDays = $workingDays['working_days'];

                if ($totalWorkingDays > 0) {
                    $newSalaryDays = $totalWorkingDays - $dayInMonth + 1;
                    $oldSalaryDays = $dayInMonth - 1;

                    // Tính lương bình quân
                    $averageSalary = round(
                        ($salaryHistory->salary / $totalWorkingDays * $newSalaryDays) +
                        ($previousSalary->salary / $totalWorkingDays * $oldSalaryDays)
                    );

                    return [
                        'basic_salary' => $averageSalary,
                        'original_salary' => $salaryHistory->salary,
                        'has_salary_change' => true,
                        'change_date' => $appliedDate
                    ];
                }
            }
        }

        return [
            'basic_salary' => $salaryHistory->salary,
            'original_salary' => $salaryHistory->salary,
            'has_salary_change' => false,
            'change_date' => null
        ];
    }

    /**
     * Tính các khoản phụ cấp
     *
     * @param User $employee
     * @param array $employeeInfo
     * @param array $salaryData
     * @param array $workingDays
     * @param array $leaveData
     * @param array $supportData
     * @return array
     */
    private function calculateAllowances(User $employee, array $employeeInfo, array $salaryData, array $workingDays, array $leaveData, array $supportData): array
    {
        $salaryHistory = $salaryData['salary_history'];
        $workingDaysPerMonth = $employeeInfo['working_days_per_month'];
        $workingHoursPerDay = $employeeInfo['working_hours_per_day'];

        // 1. Phụ cấp đi lại
        $transportationAllowance = $this->calculateTransportationAllowance(
            $employee,
            $employeeInfo,
            $workingDays,
            $supportData
        );

        // 2. Phụ cấp thâm niên công nhân
        $seniorWorkerAllowance = $this->calculateSeniorWorkerAllowance(
            $employeeInfo['company_position_id'],
            $employeeInfo['months_worked']
        );

        // 3. Phụ cấp chuyên cần
        $perfectAttendanceBonus = $this->calculatePerfectAttendanceBonus(
            $employee,
            $workingDays,
            $leaveData,
            $employeeInfo
        );

        // 4. Các phụ cấp khác từ lịch sử lương
        $positionAllowance = $salaryHistory->position_allowance;
        $evaluationAllowance = $salaryHistory->evaluation_allowance;
        $seniorityAllowance = $salaryHistory->seniority_allowance;
        $adjustmentAllowance = $salaryHistory->adjustment_allowance;
        $slippageAllowance = $salaryHistory->slippage_allowance;
        $harmfulnessAllowance = $salaryHistory->harmfulness_allowance;
        $skillAllowance = $salaryHistory->skill_allowance;
        $regularAllowance = $salaryHistory->regular_allowance;

        // Tính lương theo tỷ lệ nếu nghỉ nhiều
        $workRatio = $this->calculateWorkRatio($workingDays, $leaveData, $workingDaysPerMonth);

        if ($workRatio < 1) {
            $positionAllowance *= $workRatio;
            $evaluationAllowance *= $workRatio;
            $seniorityAllowance *= $workRatio;
            $adjustmentAllowance *= $workRatio;
            $slippageAllowance *= $workRatio;
            $harmfulnessAllowance *= $workRatio;
            $skillAllowance *= $workRatio;
            $seniorWorkerAllowance *= $workRatio;
        }

        return [
            'transportation_allowance' => $transportationAllowance,
            'position_allowance' => round($positionAllowance, 0),
            'evaluation_allowance' => round($evaluationAllowance, 0),
            'seniority_allowance' => round($seniorityAllowance, 0),
            'adjustment_allowance' => round($adjustmentAllowance, 0),
            'slippage_allowance' => round($slippageAllowance, 0),
            'harmfulness_allowance' => round($harmfulnessAllowance, 0),
            'skill_allowance' => round($skillAllowance, 0),
            'regular_allowance' => round($regularAllowance, 0),
            'senior_worker_allowance' => round($seniorWorkerAllowance, 0),
            'perfect_attendance_bonus' => $perfectAttendanceBonus,
            'salary_allowance' => $salaryData['salary_allowance']
        ];
    }

    /**
     * Tính phụ cấp đi lại
     *
     * @param User $employee
     * @param array $employeeInfo
     * @param array $workingDays
     * @param array $supportData
     * @return int
     */
    private function calculateTransportationAllowance(User $employee, array $employeeInfo, array $workingDays, array $supportData): int
    {
        $transportationId = $employeeInfo['transportation_id'];

        // Không có phương tiện hoặc nhân viên làm việc tại Nhật
        if (!$transportationId || $employeeInfo['user_status_id'] == self::WORKING_IN_JAPAN_STATUS) {
            return 0;
        }

        // Lấy mức phụ cấp
        $allowancePerMonth = $supportData['transportation_allowances'][$transportationId] ?? 0;

        if ($allowancePerMonth <= 0) {
            return 0;
        }

        // Tính theo tỷ lệ ngày làm việc (26 ngày chuẩn)
        $actualWorkingDays = $workingDays['working_days'] + $workingDays['day_off_days'];
        $allowance = round($allowancePerMonth / 26 * $actualWorkingDays);

        return max(0, $allowance);
    }

    /**
     * Tính phụ cấp thâm niên công nhân
     *
     * @param string|null $positionId
     * @param int $monthsWorked
     * @return int
     */
    private function calculateSeniorWorkerAllowance(?string $positionId, int $monthsWorked): int
    {
        // Chỉ áp dụng cho một số chức vụ nhất định
        $eligiblePositions = [1, 2, 3, 10];

        if (!in_array($positionId, $eligiblePositions)) {
            return 0;
        }

        // Thang phụ cấp theo số tháng làm việc
        if ($monthsWorked >= 13) {
            return 300000; // 300k cho >= 13 tháng
        } elseif ($monthsWorked > 6 && $monthsWorked <= 12) {
            return 200000; // 200k cho 7-12 tháng
        } elseif ($monthsWorked > 3 && $monthsWorked <= 6) {
            return 100000; // 100k cho 4-6 tháng
        }

        return 0;
    }

    /**
     * Tính thưởng chuyên cần (300k)
     *
     * @param User $employee
     * @param array $workingDays
     * @param array $leaveData
     * @param array $employeeInfo
     * @return int
     */
    private function calculatePerfectAttendanceBonus(User $employee, array $workingDays, array $leaveData, array $employeeInfo): int
    {
        // Điều kiện để được thưởng chuyên cần:
        // 1. Đủ ngày công
        // 2. Đi muộn <= 2 giờ
        // 3. Không nghỉ không phép
        // 4. Số lần đi muộn <= 1
        // 5. Không nghỉ lương cố định
        // 6. Không phải nhân viên mới
        // 7. Không phải nhân viên nghỉ việc

        $totalWorkingDays = $workingDays['working_days'] +
            $leaveData['paid_leave_days'] +
            $leaveData['day_off_70_days'] +
            $leaveData['maternity_leave_days'];

        // Kiểm tra đủ ngày công
        if ($totalWorkingDays < $workingDays['total_scheduled_days']) {
            return 0;
        }

        // Kiểm tra giờ đi muộn
        $lateHours = $this->calculateLateHours($employee);
        if ($lateHours > 2) {
            return 0;
        }

        // Kiểm tra nghỉ không lương
        if ($leaveData['other_unpaid_leave_days'] > 0) {
            return 0;
        }

        // Kiểm tra số lần đi muộn
        $lateCount = $this->countLateDays($employee);
        if ($lateCount > 1) {
            return 0;
        }

        // Kiểm tra nghỉ lương cố định
        if ($leaveData['day_off_fixed_days'] > 0) {
            return 0;
        }

        // Kiểm tra nhân viên mới hoặc nghỉ việc
        if ($employeeInfo['user_status_id'] == self::RESIGNED_STATUS ||
            $employeeInfo['user_status_id'] == self::PROBATION_STATUS) {
            return 0;
        }

        return self::PERFECT_ATTENDANCE_BONUS;
    }

    /**
     * Tính số giờ đi muộn trong tháng
     *
     * @param User $employee
     * @return float
     */
    private function calculateLateHours(User $employee): float
    {
        // Implementation would calculate late hours from shift duration checks
        // This is a placeholder for the actual calculation
        return 0;
    }

    /**
     * Đếm số ngày đi muộn
     *
     * @param User $employee
     * @return int
     */
    private function countLateDays(User $employee): int
    {
        // Implementation would count late days from shift duration checks
        // This is a placeholder for the actual calculation
        return 0;
    }

    /**
     * Tính tỷ lệ làm việc (để điều chỉnh phụ cấp)
     *
     * @param array $workingDays
     * @param array $leaveData
     * @param int $standardDays
     * @return float
     */
    private function calculateWorkRatio(array $workingDays, array $leaveData, int $standardDays): float
    {
        $actualDays = $workingDays['working_days'] + $leaveData['paid_leave_days'] + $leaveData['day_off_70_days'];

        if ($actualDays <= 7) {
            return $actualDays / $standardDays;
        }

        return 1.0;
    }

    /**
     * Lưu kết quả tính lương
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @param array $result
     * @return void
     */
    private function saveSalaryResult(int $userId, int $month, int $year, array $result): void
    {
        UserMonthlySalaryDetail::updateOrCreate(
            [
                'user_id' => $userId,
                'month' => $month,
                'year' => $year
            ],
            $result
        );
    }

    /**
     * Xóa kết quả tính lương (khi lương <= 0)
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return void
     */
    private function deleteSalaryResult(int $userId, int $month, int $year): void
    {
        UserMonthlySalaryDetail::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->delete();
    }

    /**
     * Kiểm tra có nên trả lương không
     *
     * @param User $employee
     * @param array $netSalary
     * @param array $workingDays
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return bool
     */
    private function shouldPaySalary(User $employee, array $netSalary, array $workingDays, Carbon $fromDate, Carbon $toDate): bool
    {
        // Không trả lương nếu <= 0
        if ($netSalary['net_payment'] <= 0) {
            return false;
        }

        // Kiểm tra nhân viên mới làm < 5 ngày
        if ($employee->probation_start &&
            $employee->probation_start->between($fromDate, $toDate)) {

            $workingDaysInPeriod = $employee->userWorkHours()
                ->forPeriod($employee->probation_start, $toDate)
                ->count();

            if ($workingDaysInPeriod < 5) {
                return false;
            }
        }

        return true;
    }

    /**
     * Format kết quả tính lương để lưu vào database
     *
     * @param User $employee
     * @param array $employeeInfo
     * @param array $workingDays
     * @param array $leaveData
     * @param array $overtimeData
     * @param array $nightShiftData
     * @param array $allowances
     * @param array $insurance
     * @param array $taxData
     * @param array $grossSalary
     * @param array $netSalary
     * @param int $month
     * @param int $year
     * @return array
     */
    /**
     * Format kết quả tính lương để lưu vào database
     *
     * @param User $employee
     * @param array $employeeInfo
     * @param array $workingDays
     * @param array $leaveData
     * @param array $overtimeData
     * @param array $nightShiftData
     * @param array $allowances
     * @param array $insurance
     * @param array $taxData
     * @param array $grossSalary
     * @param array $netSalary
     * @param int $month
     * @param int $year
     * @return array
     */
    private function formatSalaryResult(
        User  $employee,
        array $employeeInfo,
        array $workingDays,
        array $leaveData,
        array $overtimeData,
        array $nightShiftData,
        array $allowances,
        array $insurance,
        array $taxData,
        array $grossSalary,
        array $netSalary,
        int   $month,
        int   $year
    ): array
    {
        return [
            'username' => $employee->name,
            'department' => $employee->department->name ?? '',
            'month' => $month,
            'year' => $year,

            // Lương cơ bản
            'basic_salary' => $grossSalary['basic_salary'],

            // Phụ cấp
            'position_allowance' => $allowances['position_allowance'],
            'evaluation_allowance' => $allowances['evaluation_allowance'],
            'seniority_allowance' => $allowances['seniority_allowance'],
            'adjustment_allowance' => $allowances['adjustment_allowance'],
            'transportation_allowance' => $allowances['transportation_allowance'],
            'night_shift_allowance' => $nightShiftData['night_shift_allowance'],
            'slippage_allowance' => $allowances['slippage_allowance'],
            'harmful_allowance' => $allowances['harmfulness_allowance'],
            'regular_allowance' => $allowances['regular_allowance'],
            'seniority_worker_allowance' => $allowances['senior_worker_allowance'],
            'skill_allowance' => $allowances['skill_allowance'],
            'salary_allowance' => $allowances['salary_allowance'],

            // Tăng ca (giờ)
            'ot_day_hours' => $overtimeData['ot_day_hours'],
            'ot_night_hours' => $overtimeData['ot_night_hours'],
            'ot_dayoff_hours' => $overtimeData['ot_dayoff_hours'],
            'ot_dayoff_night_hours' => $overtimeData['ot_dayoff_night_hours'],
            'ot_special_day_hours' => $overtimeData['ot_special_day_hours'],
            'ot_special_night_hours' => $overtimeData['ot_special_night_hours'],

            // Tăng ca (tiền - chịu thuế)
            'ot_day_amount' => $grossSalary['ot_day_amount'],
            'ot_night_amount' => $grossSalary['ot_night_amount'],
            'ot_dayoff_amount' => $grossSalary['ot_dayoff_amount'],
            'ot_dayoff_night_amount' => $grossSalary['ot_dayoff_night_amount'],
            'ot_special_day_amount' => $grossSalary['ot_special_day_amount'],
            'ot_special_night_amount' => $grossSalary['ot_special_night_amount'],

            // Tăng ca (tiền - không chịu thuế)
            'ot_day_non_tax_amount' => $grossSalary['ot_day_non_tax_amount'],
            'ot_night_non_tax_amount' => $grossSalary['ot_night_non_tax_amount'],
            'ot_dayoff_non_tax_amount' => $grossSalary['ot_dayoff_non_tax_amount'],
            'ot_dayoff_night_non_tax_amount' => $grossSalary['ot_dayoff_night_non_tax_amount'],
            'ot_special_day_non_tax_amount' => $grossSalary['ot_special_day_non_tax_amount'],
            'ot_special_night_non_tax_amount' => $grossSalary['ot_special_night_non_tax_amount'],

            // Bảo hiểm
            'health_insurance' => $insurance['health_insurance'],
            'social_insurance' => $insurance['social_insurance'],
            'employment_insurance' => $insurance['employment_insurance'],
            'union_fee' => $insurance['union_fee'],

            // Khấu trừ
            'late_deduction' => $grossSalary['late_deduction'],
            'leave_deduction' => $grossSalary['leave_deduction'],

            // Phụ cấp/khấu trừ khác
            'non_taxable_allowance' => $grossSalary['non_taxable_allowance'],
            'non_taxable_deduction' => $grossSalary['non_taxable_deduction'],
            'taxable_allowance' => $grossSalary['taxable_allowance'],
            'taxable_deduction' => $grossSalary['taxable_deduction'],

            // Nghỉ phép
            'day_off_70_amount' => $grossSalary['day_off_70_amount'],
            'day_off_fixed_amount' => $grossSalary['day_off_fixed_amount'],
            'day_off_100_amount' => $grossSalary['day_off_100_amount'],
            //Thưởng chuyên cần
            'perfect_attendance_bonus' => $allowances['perfect_attendance_bonus'],

            // Ngày công
            'working_days' => $workingDays['working_days'],
            'day_off_70_days' => $leaveData['day_off_70_days'],
            'day_off_fixed_days' => $leaveData['day_off_fixed_days'],
            'day_off_100_days' => $leaveData['day_off_100_days'],
            'paid_leave_days' => $leaveData['paid_leave_days'],
            'unpaid_leave_days' => $leaveData['unpaid_leave_days'],
            'late_hours' => $grossSalary['late_hours'],
            'night_shift_hours' => $nightShiftData['night_shift_hours'],

            // Tính toán lương
            'payable_salary' => $grossSalary['payable_salary'],
            'taxable_income' => $taxData['taxable_income'],
            'pit_deduction' => $taxData['pit_deduction'],
            'after_pit_deduction' => $taxData['after_pit_deduction'],
            'personal_income_tax' => $taxData['personal_income_tax'],
            'net_payment' => $netSalary['net_payment'],

            // Trường bổ sung
            'seniority_allowance_original' => $allowances['seniority_allowance'],
            'is_custom_day_off' => false
        ];
    }

    /**
     * Tính lương gross (trước thuế)
     *
     * @param array $averageSalary
     * @param array $allowances
     * @param array $overtimeData
     * @param array $nightShiftData
     * @param array $workingDays
     * @param array $leaveData
     * @return array
     */
    private function calculateGrossSalary(array $averageSalary, array $allowances, array $overtimeData, array $nightShiftData, array $workingDays, array $leaveData): array
    {
        // Tính các khoản tăng ca (tiền)
        $overtimeAmounts = $this->calculateOvertimeAmounts($overtimeData, $averageSalary, $allowances);

        // Tính các khoản nghỉ phép được trả lương
        $leaveAmounts = $this->calculateLeaveAmounts($leaveData, $averageSalary);

        // Tính khấu trừ (đi muộn, nghỉ không phép)
        $deductions = $this->calculateDeductions($leaveData, $averageSalary);

        $payableSalary = $averageSalary['basic_salary'] +
            $allowances['salary_allowance'] +
            $leaveAmounts['day_off_70_amount'] +
            $leaveAmounts['day_off_fixed_amount'] +
            $leaveAmounts['day_off_100_amount'] -
            $deductions['leave_deduction'];

        return array_merge($overtimeAmounts, $leaveAmounts, $deductions, [
            'payable_salary' => round($payableSalary, 0),
            'late_hours' => 0, // Placeholder
            'non_taxable_allowance' => 0,
            'non_taxable_deduction' => 0,
            'taxable_allowance' => 0,
            'taxable_deduction' => 0,
            'basic_salary' => $averageSalary['basic_salary'],
        ]);
    }

    /**
     * Tính tiền tăng ca (cả chịu thuế và không chịu thuế)
     *
     * @param array $overtimeData
     * @param array $averageSalary
     * @param array $allowances
     * @return array
     */
    private function calculateOvertimeAmounts(array $overtimeData, array $averageSalary, array $allowances): array
    {
        // Tính lương giờ cơ bản (để tính OT)
        $hourlyRate = ($averageSalary['basic_salary'] +
                $allowances['harmfulness_allowance'] +
                $allowances['salary_allowance'] +
                $allowances['position_allowance'] +
                $allowances['regular_allowance'] +
                $allowances['evaluation_allowance']) / (20 * 9.5); // 20 ngày * 9.5h

        // OT1 - Tăng ca ngày (hệ số 1.5, 50% không chịu thuế)
        $otDayAmount = round($hourlyRate * $overtimeData['ot_day_hours'] * self::DAY_OT_RATE, 0);
        $otDayNonTaxAmount = round($otDayAmount * 0.5, 0);

        // OT2 - Tăng ca đêm (hệ số 1.5, phần thêm không chịu thuế)
        $otNightAmount = round($hourlyRate * $overtimeData['ot_night_hours'] * self::NIGHT_OT_RATE, 0);
        $otNightNonTaxAmount = round($otNightAmount * (2.1 - 1), 0); // Hệ số thực 2.1

        // OT3 - Tăng ca ngày nghỉ (hệ số 2.0, 100% không chịu thuế)
        $otDayoffAmount = round($hourlyRate * $overtimeData['ot_dayoff_hours'] * self::DAYOFF_OT_RATE, 0);
        $otDayoffNonTaxAmount = $otDayoffAmount;

        // OT7 - Tăng ca đêm ngày nghỉ (hệ số 2.0, phần thêm không chịu thuế)
        $otDayoffNightAmount = round($hourlyRate * $overtimeData['ot_dayoff_night_hours'] * self::DAYOFF_OT_RATE, 0);
        $otDayoffNightNonTaxAmount = round($otDayoffNightAmount * (2.7 - 1), 0); // Hệ số thực 2.7

        // OT5 - Tăng ca đặc biệt ngày (hệ số 3.0, 200% không chịu thuế)
        $otSpecialDayAmount = round($hourlyRate * $overtimeData['ot_special_day_hours'] * self::SPECIAL_OT_RATE, 0);
        $otSpecialDayNonTaxAmount = round($otSpecialDayAmount * 2, 0);

        // OT8 - Tăng ca đặc biệt đêm (hệ số 3.0, phần thêm không chịu thuế)
        $otSpecialNightAmount = round($hourlyRate * $overtimeData['ot_special_night_hours'] * self::SPECIAL_OT_RATE, 0);
        $otSpecialNightNonTaxAmount = round($otSpecialNightAmount * (3.9 - 1), 0); // Hệ số thực 3.9

        return [
            'ot_day_amount' => $otDayAmount,
            'ot_night_amount' => $otNightAmount,
            'ot_dayoff_amount' => $otDayoffAmount,
            'ot_dayoff_night_amount' => $otDayoffNightAmount,
            'ot_special_day_amount' => $otSpecialDayAmount,
            'ot_special_night_amount' => $otSpecialNightAmount,
            'ot_day_non_tax_amount' => $otDayNonTaxAmount,
            'ot_night_non_tax_amount' => $otNightNonTaxAmount,
            'ot_dayoff_non_tax_amount' => $otDayoffNonTaxAmount,
            'ot_dayoff_night_non_tax_amount' => $otDayoffNightNonTaxAmount,
            'ot_special_day_non_tax_amount' => $otSpecialDayNonTaxAmount,
            'ot_special_night_non_tax_amount' => $otSpecialNightNonTaxAmount
        ];
    }

    /**
     * Tính tiền nghỉ phép được trả lương
     *
     * @param array $leaveData
     * @param array $averageSalary
     * @return array
     */
    private function calculateLeaveAmounts(array $leaveData, array $averageSalary): array
    {
        $dailyRate = $averageSalary['basic_salary'] / 26; // 26 ngày chuẩn

        // Nghỉ 70% lương
        $dayOff70Amount = 0;
        if (($averageSalary['basic_salary']) * 0.7 < self::MIN_SALARY_FIXED) {
            $dayOff70Amount = self::MIN_SALARY_FIXED / 26 * $leaveData['day_off_70_days'];
        } else {
            $dayOff70Amount = $dailyRate * $leaveData['day_off_70_days'] * 0.7;
        }

        // Nghỉ lương cố định
        $dayOffFixedAmount = self::MIN_SALARY_FIXED / 26 * $leaveData['day_off_fixed_days'];

        // Nghỉ 100% lương
        $dayOff100Amount = $dailyRate * $leaveData['day_off_100_days'];

        return [
            'day_off_70_amount' => round($dayOff70Amount, 0),
            'day_off_fixed_amount' => round($dayOffFixedAmount, 0),
            'day_off_100_amount' => round($dayOff100Amount, 0)
        ];
    }

    /**
     * Tính các khoản khấu trừ
     *
     * @param array $leaveData
     * @param array $averageSalary
     * @return array
     */
    private function calculateDeductions(array $leaveData, array $averageSalary): array
    {
        $dailyRate = $averageSalary['basic_salary'] / 20; // 20 ngày làm việc
        $hourlyRate = $dailyRate / 9.5; // 9.5 giờ/ngày

        // Trừ lương nghỉ không phép
        $leaveDeduction = round($dailyRate * $leaveData['unpaid_leave_days'], 0);

        // Trừ lương đi muộn (placeholder - cần implement tính toán thực tế)
        $lateDeduction = 0;

        return [
            'leave_deduction' => $leaveDeduction,
            'late_deduction' => $lateDeduction
        ];
    }

    /**
     * Tính bảo hiểm (BHXH, BHYT, BHTN)
     *
     * @param User $employee
     * @param array $salaryData
     * @param array $workingDays
     * @param array $leaveData
     * @return array
     */
    private function calculateInsurance(User $employee, array $salaryData, array $workingDays, array $leaveData): array
    {
        $salaryHistory = $salaryData['salary_history'];

        // Lương để tính bảo hiểm
        $salaryForInsurance = $salaryHistory->salary +
            $salaryData['salary_allowance'] +
            $salaryHistory->position_allowance +
            $salaryHistory->evaluation_allowance +
            $salaryHistory->regular_allowance +
            $salaryHistory->harmfulness_allowance +
            $salaryHistory->skill_allowance +
            $salaryHistory->seniority_allowance +
            $salaryHistory->adjustment_allowance;

        // Kiểm tra điều kiện tham gia bảo hiểm
        $totalLeaveDays = $leaveData['unpaid_leave_days'] - $leaveData['day_off_fixed_days'];
        $isEligibleForInsurance = ($totalLeaveDays / 8) < 14; // < 14 ngày nghỉ không phép

        // Nhân viên tạm thời có tỷ lệ khác
        $isTemporaryWorker = ($employee->employeeStatuses()
                ->forDate(now())
                ->first()?->status_id == self::TEMPORARY_WORK_STATUS);

        if ($isTemporaryWorker) {
            // Nhân viên tạm thời: tỷ lệ cao hơn, không có BHTN
            $healthInsurance = $this->calculateInsuranceAmount($salaryForInsurance, 0.03, 0, PHP_INT_MAX);
            $socialInsurance = $this->calculateInsuranceAmount($salaryForInsurance, 0.16, 0, PHP_INT_MAX);
            $employmentInsurance = 0;
        } else {
            // Nhân viên chính thức
            if ($isEligibleForInsurance && $salaryHistory->health_insurance_included) {
                $healthInsurance = $this->calculateInsuranceAmount(
                    $salaryForInsurance,
                    self::HEALTH_INSURANCE_RATE,
                    self::INSURANCE_MIN,
                    self::HEALTH_INSURANCE_MAX
                );
            } else {
                $healthInsurance = 0;
            }

            if ($isEligibleForInsurance && $salaryHistory->social_insurance_included) {
                $socialInsurance = $this->calculateInsuranceAmount(
                    $salaryForInsurance,
                    self::SOCIAL_INSURANCE_RATE,
                    self::INSURANCE_MIN,
                    self::SOCIAL_INSURANCE_MAX
                );

                $employmentInsurance = $this->calculateInsuranceAmount(
                    $salaryForInsurance,
                    self::EMPLOYMENT_INSURANCE_RATE,
                    self::INSURANCE_MIN,
                    self::EMPLOYMENT_INSURANCE_MAX
                );
            } else {
                $socialInsurance = 0;
                $employmentInsurance = 0;
            }
        }

        return [
            'health_insurance' => round($healthInsurance, 0),
            'social_insurance' => round($socialInsurance, 0),
            'employment_insurance' => round($employmentInsurance, 0),
            'union_fee' => $salaryHistory->union_fee ?? 0
        ];
    }

    /**
     * Tính tiền bảo hiểm theo mức lương
     *
     * @param float $salary
     * @param float $rate
     * @param float $min
     * @param float $max
     * @return float
     */
    private function calculateInsuranceAmount(float $salary, float $rate, float $min, float $max): float
    {
        // Áp dụng giới hạn tối thiểu và tối đa
        $adjustedSalary = max($min, min($salary, $max));

        return $adjustedSalary * $rate;
    }

    /**
     * Tính thuế thu nhập cá nhân
     *
     * @param User $employee
     * @param array $grossSalary
     * @param array $insurance
     * @return array
     */
    private function calculatePersonalIncomeTax(User $employee, array $grossSalary, array $insurance): array
    {
        // Thu nhập chịu thuế = lương gross + phụ cấp chịu thuế - bảo hiểm
        $taxableIncome = $grossSalary['payable_salary'] +
            $grossSalary['ot_day_amount'] +
            $grossSalary['ot_night_amount'] +
            $grossSalary['ot_dayoff_amount'] +
            $grossSalary['ot_dayoff_night_amount'] +
            $grossSalary['ot_special_day_amount'] +
            $grossSalary['ot_special_night_amount'] +
            $grossSalary['taxable_allowance'] -
            $grossSalary['taxable_deduction'] -
            $insurance['health_insurance'] -
            $insurance['social_insurance'] -
            $insurance['employment_insurance'] -
            $insurance['union_fee'];

        // Lấy số người phụ thuộc
        $dependents = $employee->personalIncomeTaxDeductions()
            ->forDate(now())
            ->first();
        $dependentsCount = $dependents?->number_of_dependents ?? 0;

        // Giảm trừ gia cảnh
        $pitDeduction = self::SELF_DEDUCTION + ($dependentsCount * self::DEPENDENT_DEDUCTION);

        // Thu nhập sau giảm trừ gia cảnh
        $afterPitDeduction = max(0, $taxableIncome - $pitDeduction);

        // Tính thuế theo bảng lũy tiến từng phần
        $personalIncomeTax = $this->calculateProgressiveTax($afterPitDeduction);

        return [
            'taxable_income' => round($taxableIncome, 0),
            'pit_deduction' => round($pitDeduction, 0),
            'after_pit_deduction' => round($afterPitDeduction, 0),
            'personal_income_tax' => round($personalIncomeTax, 0)
        ];
    }

    /**
     * Tính thuế theo bảng lũy tiến từng phần
     *
     * @param float $taxableAmount
     * @return float
     */
    private function calculateProgressiveTax(float $taxableAmount): float
    {
        if ($taxableAmount <= 0) {
            return 0;
        }

        // Bảng thuế lũy tiến từng phần (VND)
        $taxBrackets = [
            [0, 5000000, 0.05],           // 0-5M: 5%
            [5000000, 10000000, 0.10],    // 5M-10M: 10%
            [10000000, 18000000, 0.15],   // 10M-18M: 15%
            [18000000, 32000000, 0.20],   // 18M-32M: 20%
            [32000000, 52000000, 0.25],   // 32M-52M: 25%
            [52000000, 80000000, 0.30],   // 52M-80M: 30%
            [80000000, PHP_INT_MAX, 0.35] // >80M: 35%
        ];

        $totalTax = 0;
        $remainingAmount = $taxableAmount;

        foreach ($taxBrackets as [$min, $max, $rate]) {
            if ($remainingAmount <= 0) {
                break;
            }

            $taxableInBracket = min($remainingAmount, $max - $min);
            $totalTax += $taxableInBracket * $rate;
            $remainingAmount -= $taxableInBracket;
        }

        return $totalTax;
    }

    /**
     * Tính lương thực lãnh (net salary)
     *
     * @param array $grossSalary
     * @param array $insurance
     * @param array $taxData
     * @param array $nightShiftData
     * @param array $salaryData
     * @return array
     */
    private function calculateNetSalary(array $grossSalary, array $insurance, array $taxData, array $nightShiftData, array $salaryData): array
    {
        $salaryHistory = $salaryData['salary_history'];

        // Lương thực lãnh = Thu nhập chịu thuế - Thuế TNCN + Các khoản không chịu thuế
        $netPayment = $taxData['taxable_income'] -
            $taxData['personal_income_tax'] +
            $nightShiftData['night_shift_allowance'] +
            $salaryHistory->harmfulness_allowance +
            $grossSalary['ot_day_non_tax_amount'] +
            $grossSalary['ot_night_non_tax_amount'] +
            $grossSalary['ot_dayoff_non_tax_amount'] +
            $grossSalary['ot_dayoff_night_non_tax_amount'] +
            $grossSalary['ot_special_day_non_tax_amount'] +
            $grossSalary['ot_special_night_non_tax_amount'] +
            $grossSalary['non_taxable_allowance'] -
            $grossSalary['non_taxable_deduction'];

        return [
            'net_payment' => round($netPayment, 0)
        ];
    }
}
