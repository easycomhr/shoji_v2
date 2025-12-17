<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->truncate();
        DB::table('skills')->insert([
            // Kỹ năng nhân sự
            [
                'company_id' => 1,
                'code' => 'HR001',
                'name' => 'Team Management',
                'allowance' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'HR002',
                'name' => 'Conflict Resolution',
                'allowance' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'HR003',
                'name' => 'Recruitment',
                'allowance' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'HR004',
                'name' => 'Training & Development',
                'allowance' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kỹ năng về ngôn ngữ
            [
                'company_id' => 1,
                'code' => 'LANG001',
                'name' => 'English - Fluent',
                'allowance' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'LANG002',
                'name' => 'Japanese - N2',
                'allowance' => 350000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'LANG003',
                'name' => 'French - Intermediate',
                'allowance' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'LANG004',
                'name' => 'Chinese - Advanced',
                'allowance' => 320000,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kỹ năng lái xe
            [
                'company_id' => 1,
                'code' => 'DRIVE001',
                'name' => 'Car Driving License - B1',
                'allowance' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'DRIVE002',
                'name' => 'Car Driving License - C',
                'allowance' => 150000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'DRIVE003',
                'name' => 'Motorbike License - A',
                'allowance' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
