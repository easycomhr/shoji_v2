<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('education')->truncate();
        DB::table('education')->insert([
            [
                'company_id' => 1,
                'code' => 'EDU001',
                'name' => 'Primary School',
                'note' => 'Elementary education for young children.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU002',
                'name' => 'Secondary School',
                'note' => 'Middle education after primary school.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU003',
                'name' => 'High School',
                'note' => 'Higher education preparing for college or university.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU004',
                'name' => 'Vocational Training',
                'note' => 'Education focused on hands-on skills and trades.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU005',
                'name' => 'College',
                'note' => 'Undergraduate education beyond high school.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU006',
                'name' => 'University',
                'note' => 'Undergraduate or postgraduate education at an institution.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU007',
                'name' => 'Master’s Degree',
                'note' => 'Postgraduate education focusing on specialized fields.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'EDU008',
                'name' => 'Doctorate Degree',
                'note' => 'Highest level of academic achievement, often in research.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
