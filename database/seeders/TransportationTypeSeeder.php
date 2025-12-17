<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transportation_types')->truncate();
        $types = [
            ['code' => 'A', 'name' => 'A', 'note' => 'Type A'],
            ['code' => 'B', 'name' => 'B', 'note' => 'Type B'],
            ['code' => 'C', 'name' => 'C', 'note' => 'Type C'],
        ];

        foreach ($types as $type) {
            DB::table('transportation_types')->insert([
                'company_id' => 1,
                'code' => $type['code'],
                'name' => $type['name'],
                'note' => $type['note'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
