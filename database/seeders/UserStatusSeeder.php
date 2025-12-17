<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_statuses')->truncate();
        $statuses = [
            ['code' => 'TS', 'name' => 'Thai sản'],
            ['code' => 'HS', 'name' => 'Hậu sản'],
            ['code' => 'NW', 'name' => 'Làm việc bình thường'],
            ['code' => 'TER', 'name' => 'Nghỉ việc'],
            ['code' => 'LL', 'name' => 'Nghỉ dài hạn'],
        ];

        foreach ($statuses as $status) {
            $status['company_id'] = 1;
            DB::table('user_statuses')->insert($status);
        }
    }
}
