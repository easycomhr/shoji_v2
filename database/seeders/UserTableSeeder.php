<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'company_id' => 1,
                'code' => 'admin',
                'name' => 'Admin',
                'email' => 'admin@hrms.com',
                'password' => Hash::make('password1234'),
                'department_id' => 1,
                'division_id' => 1,
                'office_id' => 1,
                'access_level' => 1,
                'role' => 1, // Changed to integer
                'is_system_user' => true,
                'is_blocked' => false,
                'is_login' => true,
                'lasted_login' => now(),
                'join_date' => '2023-01-01',
                'employee_code' => 'EMP-ADM-001',
                'phone' => '+1234567890',
                'company_email' => 'admin@hrms.com',
                'language' => 'en',
                'theme' => 'light',
                'notes' => 'System administrator with full access to all modules.',
                'user_status_id' => 1,
                'modules_allowed' => json_encode(['all']),
                'is_office' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'timeKeeper',
                'name' => 'TimeKeeper',
                'email' => 'timekeeper@hrms.com',
                'password' => Hash::make('password1234'),
                'department_id' => 2,
                'division_id' => 2,
                'office_id' => 1,
                'access_level' => 2,
                'role' => 2, // Changed to integer
                'is_system_user' => true,
                'is_blocked' => false,
                'is_login' => true,
                'lasted_login' => now(),
                'join_date' => '2023-06-15',
                'employee_code' => 'EMP-TMK-001',
                'timekeeper_card_id' => 'TKC001',
                'phone' => '+1234567891',
                'company_email' => 'timekeeper@hrms.com',
                'language' => 'en',
                'theme' => 'light',
                'notes' => 'Responsible for managing employee timesheets and attendance.',
                'user_status_id' => 1,
                'modules_allowed' => json_encode(['timesheet', 'attendance']),
                'is_office' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'code' => 'accountant',
                'name' => 'Accountant',
                'email' => 'accountant@hrms.com',
                'password' => Hash::make('password1234'),
                'department_id' => 3,
                'division_id' => 3,
                'office_id' => 1,
                'access_level' => 3,
                'role' => 3, // Changed to integer
                'is_system_user' => true,
                'is_blocked' => false,
                'is_login' => true,
                'lasted_login' => now(),
                'join_date' => '2023-03-10',
                'employee_code' => 'EMP-ACC-001',
                'phone' => '+1234567892',
                'company_email' => 'accountant@hrms.com',
                'language' => 'en',
                'theme' => 'light',
                'notes' => 'Manages financial records and payroll.',
                'user_status_id' => 1,
                'modules_allowed' => json_encode(['finance', 'payroll']),
                'is_office' => true,
                'first_basic_salary' => 5000.00,
                'bank_account_number' => '123456789012',
                'bank_name' => 'Example Bank',
                'bank_branch' => 'Main Branch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}