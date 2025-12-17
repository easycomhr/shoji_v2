<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->truncate();

        $positions = [
            ['name' => 'Tổng Giám Đốc', 'code' => 'TGD'],
            ['name' => 'Quản Lý Bán Hàng', 'code' => 'QLBH'],
            ['name' => 'Kế Toán Trưởng', 'code' => 'KTT'],
            ['name' => 'Quản Lý Xuống', 'code' => 'QLX'],
            ['name' => 'Nhân Viên Văn Phòng', 'code' => 'NVP'],
            ['name' => 'Thư Ký', 'code' => 'TK'],
            ['name' => 'Trợ Lý Kế Toán', 'code' => 'TLKT'],
            ['name' => 'Trợ Lý Bán Hàng', 'code' => 'TLBH'],
            ['name' => 'Nhân Viên Thông Dịch, Tồng Vụ', 'code' => 'TD-TV'],
            ['name' => 'Nhân Viên Xuống', 'code' => 'CNX'],
        ];

        foreach ($positions as $position) {
            $position['company_id'] = 1;
            Position::create($position);
        }


    }
}