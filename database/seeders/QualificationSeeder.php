<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('qualifications')->insert([
            [
                'company_id' => 1,
                'code' => 'QUAL001',
                'name' => 'Primary School',
                'note' => 'Completed primary education level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL002',
                'name' => 'Secondary School',
                'note' => 'Completed secondary education level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL003',
                'name' => 'High School',
                'note' => 'Completed high school education level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL004',
                'name' => 'Vocational Training',
                'note' => 'Completed vocational education level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL005',
                'name' => 'College',
                'note' => 'Completed college education level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL006',
                'name' => 'University',
                'note' => 'Completed university degree (e.g., Bachelor).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL007',
                'name' => 'Master’s Degree',
                'note' => 'Completed Master’s level education.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'QUAL008',
                'name' => 'Doctorate Degree',
                'note' => 'Completed Doctorate (Ph.D.) education.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
