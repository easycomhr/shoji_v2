<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        EmployeeStatus::truncate();

        // Lấy tất cả users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  Không có user nào trong database!');
            return;
        }

        // Chuẩn bị dữ liệu batch
        $batchData = [];
        $now = now();

        foreach ($users as $user) {
            $appliedDate = $user->probation_start ?? $user->created_at ?? $now;

            $batchData[] = [
                'user_id' => $user->id,
                'status_id' => 3, // NW - Làm việc bình thường
                'applied_date' => $appliedDate->format('Y-m-d'),
                'notes' => 'Nhân viên đang làm việc bình thường',
                'created_at' => $now,
                'updated_at' => $now
            ];

            $this->command->info("👤 User {$user->id}: {$user->name} → NW (Làm việc bình thường)");
        }

        // Insert batch
        EmployeeStatus::insert($batchData);

        $this->command->info("✅ Đã tạo trạng thái NW cho {$users->count()} nhân viên!");
        $this->command->info("📊 Tất cả nhân viên hiện đang ở trạng thái 'Làm việc bình thường'");
    }
}
