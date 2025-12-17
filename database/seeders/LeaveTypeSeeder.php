<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('leave_types')->truncate();

        $leaveTypes = [
            ['name' => 'Nghỉ phép năm', 'paid_rate' => 100, 'code' => 'LT01', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ có phép', 'paid_rate' => 100, 'code' => 'LT02', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ không phép', 'paid_rate' => 0, 'code' => 'LT03', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ không lương', 'paid_rate' => 0, 'code' => 'LT04', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ đột xuất', 'paid_rate' => 100, 'code' => 'LT05', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ đột xuất KL', 'paid_rate' => 0, 'code' => 'LT06', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ chế độ', 'paid_rate' => 100, 'code' => 'LT07', 'leave_category_id' => 8], // No
            ['name' => 'Công tác', 'paid_rate' => 100, 'code' => 'LT08', 'leave_category_id' => 3], // Business Trip
            ['name' => 'Nghỉ tang', 'paid_rate' => 100, 'code' => 'LT09', 'leave_category_id' => 8], // No
            ['name' => 'Nuôi con nhỏ', 'paid_rate' => 100, 'code' => 'LT10', 'leave_category_id' => 1], // Maternity
            ['name' => 'Nghỉ cưới', 'paid_rate' => 100, 'code' => 'LT11', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ 85%', 'paid_rate' => 85, 'code' => 'LT12', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ bù', 'paid_rate' => 100, 'code' => 'LT13', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ ĐXGB', 'paid_rate' => 100, 'code' => 'LT14', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ ĐXGB KL', 'paid_rate' => 0, 'code' => 'LT15', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ chế độ ĐX', 'paid_rate' => 100, 'code' => 'LT16', 'leave_category_id' => 8], // No
            ['name' => 'Nuôi con nhỏ ĐX', 'paid_rate' => 100, 'code' => 'LT17', 'leave_category_id' => 1], // Maternity
            ['name' => 'Nghỉ bù ĐX', 'paid_rate' => 100, 'code' => 'LT18', 'leave_category_id' => 8], // No
            ['name' => 'Nghỉ 50%', 'paid_rate' => 50, 'code' => 'LT19', 'leave_category_id' => 8], // No
            ['name' => 'Phép phụ nữ', 'paid_rate' => 100, 'code' => 'LT20', 'leave_category_id' => 1], // Maternity
            ['name' => 'Phép nghỉ khám NVQS', 'paid_rate' => 100, 'code' => 'LT21', 'leave_category_id' => 8], // No
            ['name' => 'Kỹ niệm thành lập công ty 03/03', 'paid_rate' => 100, 'code' => 'LT22', 'leave_category_id' => 6], // Company Event
            ['name' => 'Nghỉ hằng năm 31/12', 'paid_rate' => 100, 'code' => 'LT23', 'leave_category_id' => 5], // Holiday
            ['name' => 'Nghỉ ngày thứ 7 hằng tháng', 'paid_rate' => 100, 'code' => 'LT24', 'leave_category_id' => 8], // No
        ];

        foreach ($leaveTypes as $leaveType) {
            $leaveType['company_id'] = 1;
            DB::table('leave_types')->insert($leaveType);
        }


    }
}