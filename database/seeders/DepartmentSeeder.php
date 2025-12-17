<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->truncate();

        $departments = [
            [
                'company_id' => 1,
                'code' => 'ADMIN',
                'name' => 'System Admin',
                'note' => null,
            ],
            [
                'company_id' => 1,
                'code' => 'VP',
                'name' => 'VĂN PHÒNG',
                'note' => null,
            ],
            [
                'company_id' => 1,
                'code' => 'MAY',
                'name' => 'MAY',
                'note' => null,
            ],
            [
                'company_id' => 1,
                'code' => 'DET',
                'name' => 'DỆT',
                'note' => null,
            ],
            [
                'company_id' => 1,
                'code' => 'TN',
                'name' => 'TẨY NHUỘM',
                'note' => null,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }


    }
}