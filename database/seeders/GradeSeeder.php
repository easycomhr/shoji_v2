<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('grades')->truncate();
        DB::table('grades')->insert([
            [
                'company_id' => 1,
                'code' => 'GRADE001',
                'name' => 'Normal',
                'note' => 'Basic achievement level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'GRADE002',
                'name' => 'Good',
                'note' => 'Above average performance.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'GRADE003',
                'name' => 'Very Good',
                'note' => 'Excellent performance with minor issues.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'GRADE004',
                'name' => 'Excellent',
                'note' => 'Outstanding performance.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'GRADE005',
                'name' => 'Outstanding',
                'note' => 'Achievement far exceeds expectations.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
