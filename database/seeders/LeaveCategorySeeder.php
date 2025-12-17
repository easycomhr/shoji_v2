<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_categories')->truncate();
        $list = [
            ['code' => 'A', 'name' => 'Maternity', 'note' => 'Category for maternity leave'],
            ['code' => 'B', 'name' => 'Training', 'note' => 'Category for training leave'],
            ['code' => 'C', 'name' => 'Business Trip', 'note' => 'Category for business trip leave'],
            ['code' => 'D', 'name' => 'Health Care', 'note' => 'Category for health care leave'],
            ['code' => 'E', 'name' => 'Holiday', 'note' => 'Category for holiday leave'],
            ['code' => 'F', 'name' => 'Company Event', 'note' => 'Category for company event leave'],
            ['code' => 'G', 'name' => 'Black Out', 'note' => 'Category for black out leave'],
            ['code' => 'H', 'name' => 'No', 'note' => 'Category for no leave'],
        ];

        foreach ($list as $key => $item) {
            DB::table('leave_categories')->insert([
                'company_id' => 1,
                'code' => $item['code'],
                'name' => $item['name'],
                'note' => $item['note'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
