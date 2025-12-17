<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalaryHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả user từ bảng users
        $users = User::get();

        if ($users->isEmpty()) {
            $this->command->warn('Không có user nào trong database. Vui lòng chạy UserSeeder trước.');
            return;
        }

        // Xóa dữ liệu cũ một cách an toàn
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            SalaryHistory::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {
            // Nếu truncate không được, xóa từng bản ghi
            SalaryHistory::query()->delete();
        }

        $salaryHistories = [];
        $now = now();

        foreach ($users as $user) {
            // Tạo 2-4 bản ghi lịch sử lương cho mỗi nhân viên
            $numberOfRecords = 1;
            $userId = $user->id;

            for ($i = 0; $i < $numberOfRecords; $i++) {
                $appliedDate = $user->probation_start ?? $user->created_at ?? $now;

                // Lương cơ bản tăng dần theo thời gian
                $baseSalary = $this->generateBaseSalary($i);

                $salaryHistories[] = [
                    'user_id' => $userId,
                    'applied_date' => $appliedDate,
                    'salary' => $baseSalary,
                    'position_allowance' => $this->generatePositionAllowance(),
                    'evaluation_allowance' => $this->generateEvaluationAllowance(),
                    'harmfulness_allowance' => $this->generateHarmfulnessAllowance(),
                    'skill_allowance' => $this->generateSkillAllowance(),
                    'seniority_allowance' => $this->generateSeniorityAllowance($i),
                    'adjustment_allowance' => $this->generateAdjustmentAllowance(),
                    'slippage_allowance' => $this->generateSlippageAllowance(),
                    'regular_allowance' => $this->generateRegularAllowance(),
                    'union_fee' => $this->generateUnionFee(),
                    'health_insurance_included' => rand(0, 1),
                    'social_insurance_included' => rand(0, 1),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Insert dữ liệu theo batch để tối ưu performance
        $chunks = array_chunk($salaryHistories, 500);
        foreach ($chunks as $chunk) {
            SalaryHistory::insert($chunk);
        }

        $this->command->info('Đã tạo ' . count($salaryHistories) . ' bản ghi SalaryHistory cho ' . $users->count() . ' nhân viên.');
    }

    /**
     * Tạo lương cơ bản ngẫu nhiên theo cấp độ
     */
    private function generateBaseSalary($level = 0): int
    {
        $baseSalaries = [
            8000000,  // Nhân viên mới
            10000000, // Nhân viên có kinh nghiệm
            15000000, // Nhân viên senior
            20000000, // Team lead/Manager
        ];

        $baseIndex = min($level, count($baseSalaries) - 1);
        $base = $baseSalaries[$baseIndex];

        // Thêm biến động ±20%
        $variation = rand(-20, 20) / 100;

        return (int) round($base * (1 + $variation), -4); // Làm tròn đến chục nghìn
    }

    /**
     * Tạo phụ cấp chức vụ ngẫu nhiên
     */
    private function generatePositionAllowance(): int
    {
        $allowances = [0, 500000, 1000000, 1500000, 2000000, 3000000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp đánh giá ngẫu nhiên
     */
    private function generateEvaluationAllowance(): int
    {
        $allowances = [0, 200000, 500000, 800000, 1000000, 1500000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp độc hại ngẫu nhiên
     */
    private function generateHarmfulnessAllowance(): int
    {
        // 70% không có phụ cấp độc hại
        if (rand(1, 100) <= 70) {
            return 0;
        }

        $allowances = [500000, 800000, 1000000, 1200000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp kỹ năng ngẫu nhiên
     */
    private function generateSkillAllowance(): int
    {
        $allowances = [0, 300000, 500000, 800000, 1000000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp thâm niên tăng dần theo thời gian
     */
    private function generateSeniorityAllowance($level = 0): int
    {
        $baseAllowance = $level * 200000; // Tăng 200k mỗi kỳ
        $variation = rand(-50000, 100000); // Biến động nhỏ

        return max(0, $baseAllowance + $variation);
    }

    /**
     * Tạo phụ cấp điều chỉnh ngẫu nhiên
     */
    private function generateAdjustmentAllowance(): int
    {
        // 80% không có phụ cấp điều chỉnh
        if (rand(1, 100) <= 80) {
            return 0;
        }

        $allowances = [200000, 300000, 500000, 800000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp trượt ca ngẫu nhiên
     */
    private function generateSlippageAllowance(): int
    {
        // 60% không có phụ cấp trượt ca
        if (rand(1, 100) <= 60) {
            return 0;
        }

        $allowances = [300000, 500000, 700000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phụ cấp thường xuyên ngẫu nhiên
     */
    private function generateRegularAllowance(): int
    {
        $allowances = [0, 200000, 300000, 500000];
        return $allowances[array_rand($allowances)];
    }

    /**
     * Tạo phí công đoàn ngẫu nhiên
     */
    private function generateUnionFee(): int
    {
        $fees = [0, 20000, 30000, 50000];
        return $fees[array_rand($fees)];
    }
}