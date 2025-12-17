<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->insert([
            'name' => 'Trim Manufacturing',
            'official_number' => 'TM-001',
            'address_line_1' => '123 Main Street',
            'address_line_2' => 'Tầng 2',
            'address_line_3' => 'Phường Shibuya',
            'address_line_4' => 'Quận Shibuya',
            'address_line_5' => 'Tokyo',
            'address_line_6' => 'Nhật Bản',
            'phone' => '+81-3-1234-5678',
            'fax' => '+81-3-8765-4321',
            'email' => 'contact@trimmanufacturing.jp',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
        ]);
    }
}