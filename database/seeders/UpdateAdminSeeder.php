<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')
            ->where('email', 'admin@hrms.com')
            ->update(['password' => Hash::make('password1234')]);
    }
}
