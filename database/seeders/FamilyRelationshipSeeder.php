<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('family_relationships')->truncate();

        DB::table('family_relationships')->insert([
            [
                'company_id' => 1,
                'code' => 'REL001',
                'name' => 'Father',
                'note' => 'Biological or adoptive father.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL002',
                'name' => 'Mother',
                'note' => 'Biological or adoptive mother.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL003',
                'name' => 'Son',
                'note' => 'Biological or adopted son.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL004',
                'name' => 'Daughter',
                'note' => 'Biological or adopted daughter.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL005',
                'name' => 'Brother',
                'note' => 'Biological or adopted brother.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL006',
                'name' => 'Sister',
                'note' => 'Biological or adopted sister.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL007',
                'name' => 'Spouse',
                'note' => 'Husband or wife.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL008',
                'name' => 'Grandfather',
                'note' => 'Father of a parent.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL009',
                'name' => 'Grandmother',
                'note' => 'Mother of a parent.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'REL010',
                'name' => 'Cousin',
                'note' => 'Child of an aunt or uncle.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
