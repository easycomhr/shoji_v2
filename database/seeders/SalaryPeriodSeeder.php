<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalaryPeriod;
use Carbon\Carbon;

/**
 * SalaryPeriodSeeder - Tạo dữ liệu mẫu cho kỳ lương
 *
 * Tạo các kỳ lương theo:
 * - Từ 2024 đến 2025 (2 năm)
 * - Mỗi tháng 1 kỳ lương
 * - Tự động tính ngày bắt đầu/kết thúc
 * - Tự động tính số ngày làm việc chuẩn
 */
class SalaryPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ (nếu có)
        SalaryPeriod::truncate();

        // Tạo kỳ lương từ 2023 đến 2025
        $this->createSalaryPeriods(2024, 2025);

        $this->command->info('✅ Đã tạo thành công ' . SalaryPeriod::count() . ' kỳ lương!');
    }

    /**
     * Tạo kỳ lương cho khoảng thời gian
     *
     * @param int $fromYear - Năm bắt đầu
     * @param int $toYear - Năm kết thúc
     */
    private function createSalaryPeriods(int $fromYear, int $toYear): void
    {
        for ($year = $fromYear; $year <= $toYear; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $this->createMonthlySalaryPeriod($year, $month);
            }
        }
    }

    /**
     * Tạo kỳ lương cho 1 tháng cụ thể
     *
     * @param int $year - Năm
     * @param int $month - Tháng
     */
    private function createMonthlySalaryPeriod(int $year, int $month): void
    {
        // Tính ngày bắt đầu và kết thúc của kỳ lương
        $fromDate = Carbon::create($year, $month, 1);
        $toDate = $fromDate->copy()->endOfMonth();

        // Tính số ngày làm việc chuẩn (loại trừ chủ nhật)
        $standardWorkingDays = $this->calculateStandardWorkingDays($fromDate, $toDate);

        // Tạo tên kỳ lương
        $periodName = $this->generatePeriodName($year, $month);

        // Xác định trạng thái khóa (khóa những tháng cũ)
        $isLocked = $this->shouldLockPeriod($year, $month);

        SalaryPeriod::create([
            'name' => $periodName,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'standard_working_days' => $standardWorkingDays,
            'is_locked' => $isLocked,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info("📅 Đã tạo kỳ lương: {$periodName} ({$standardWorkingDays} ngày)");
    }

    /**
     * Tính số ngày làm việc chuẩn trong tháng
     * Loại trừ chủ nhật và một số ngày lễ cố định
     *
     * @param Carbon $fromDate
     * @param Carbon $toDate
     * @return int
     */
    private function calculateStandardWorkingDays(Carbon $fromDate, Carbon $toDate): int
    {
        $workingDays = 0;
        $currentDate = $fromDate->copy();

        // Danh sách ngày lễ cố định (theo dương lịch)
        $fixedHolidays = $this->getFixedHolidays($fromDate->year);

        while ($currentDate->lte($toDate)) {
            // Bỏ qua chủ nhật (dayOfWeek = 0)
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                // Kiểm tra có phải ngày lễ không
                $dateString = $currentDate->format('m-d');
                if (!in_array($dateString, $fixedHolidays)) {
                    $workingDays++;
                }
            }

            $currentDate->addDay();
        }

        // Đảm bảo ít nhất 20 ngày, nhiều nhất 23 ngày
        return max(20, min(23, $workingDays));
    }

    /**
     * Lấy danh sách ngày lễ cố định trong năm
     *
     * @param int $year
     * @return array
     */
    private function getFixedHolidays(int $year): array
    {
        return [
            '01-01', // Tết Dương lịch
            '04-30', // 30/4
            '05-01', // Quốc tế Lao động
            '09-02', // Quốc khánh
        ];
    }

    /**
     * Tạo tên kỳ lương
     *
     * @param int $year
     * @param int $month
     * @return string
     */
    private function generatePeriodName(int $year, int $month): string
    {
        $monthNames = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];

        return $monthNames[$month] . ' năm ' . $year;
    }

    /**
     * Xác định có nên khóa kỳ lương này không
     * Khóa những kỳ cũ hơn 2 tháng so với hiện tại
     *
     * @param int $year
     * @param int $month
     * @return bool
     */
    private function shouldLockPeriod(int $year, int $month): bool
    {
        $periodDate = Carbon::create($year, $month, 1);
        $twoMonthsAgo = now()->subMonths(2)->startOfMonth();

        return $periodDate->lt($twoMonthsAgo);
    }
}
