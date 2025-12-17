<?php

use Carbon\Carbon;

if (!function_exists('convertDateFormat')) {
    /**
     * Convert date from d/m/Y format to Y-m-d format
     *
     * @param string $date Date in d/m/Y format
     * @param string $fromFormat Input format (default: 'd/m/Y')
     * @param string $toFormat Output format (default: 'Y-m-d')
     * @return string|null
     */
    function convertDateFormat($date, $fromFormat = 'd/m/Y', $toFormat = 'Y-m-d')
    {
        try {
            if (empty($date)) {
                return null;
            }
            return Carbon::createFromFormat($fromFormat, $date)->format($toFormat);
        } catch (Exception $e) {
            return null;
        }
    }

    // Function để tính phụ cấp ca đêm
    if (!function_exists('calculateNightShiftAllowance')) {
        /**
         * Tính phụ cấp ca đêm
         *
         * @param float $nightShiftDays - Số ngày ca đêm
         * @param float $nightShiftRate - Hệ số phụ cấp (0.3 hoặc 0.35)
         * @param float $dailyRate - Mức lương ngày
         * @return int
         */
        function calculateNightShiftAllowance(float $nightShiftDays, float $nightShiftRate, float $dailyRate): int
        {
            return round($nightShiftDays * $nightShiftRate * $dailyRate, 0);
        }
    }

// Function để kiểm tra nhân viên có nghỉ phép không
    if (!function_exists('isLeaveDay')) {
        /**
         * Kiểm tra nhân viên có nghỉ phép ngày này không
         *
         * @param User $user
         * @param Carbon $date
         * @return bool
         */
        function isLeaveDay(User $user, Carbon $date): bool
        {
            return $user->userLeaves()
                ->where('leave_date', $date)
                ->approved()
                ->exists();
        }
    }

// Function để tính ngày cuối tháng
    if (!function_exists('getLastDayOfMonth')) {
        /**
         * Lấy ngày cuối tháng
         *
         * @param int $month
         * @param int $year
         * @return int
         */
        function getLastDayOfMonth(int $month, int $year): int
        {
            return Carbon::create($year, $month, 1)->endOfMonth()->day;
        }
    }

// Function để tính khoảng cách thời gian
    if (!function_exists('differenceDateTime')) {
        /**
         * Tính khoảng cách giữa 2 thời điểm (giây)
         *
         * @param string $datetime1
         * @param string $datetime2
         * @return int
         */
        function differenceDateTime(string $datetime1, string $datetime2): int
        {
            $date1 = Carbon::parse($datetime1);
            $date2 = Carbon::parse($datetime2);

            return $date2->diffInSeconds($date1, false);
        }
    }

    // Function để thêm ngày
    if (!function_exists('Date_Add_In_YMDFormat')) {
        /**
         * Thêm ngày vào một ngày
         *
         * @param string $date - Ngày gốc (Y-m-d)
         * @param int $days - Số ngày cần thêm
         * @return string
         */
        function Date_Add_In_YMDFormat(string $date, int $days): string
        {
            return Carbon::parse($date)->addDays($days)->format('Y-m-d');
        }
    }
}