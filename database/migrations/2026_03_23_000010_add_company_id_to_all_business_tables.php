<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that need company_id added.
     * Excludes system/infra tables: migrations, failed_jobs, password_reset_tokens,
     * personal_access_tokens, login_audit_logs, companies, language_translations,
     * application_modules, application_resources, migration_logs.
     */
    private array $tables = [
        'accumulation_leaves',
        'annual_leave_by_month',
        'company_positions',
        'divisions',
        'employee_allowances',
        'employee_family_members',
        'employee_positions',
        'employee_statuses',
        'final_timesheets',
        'group_histories',
        'groups',
        'holidays',
        'labour_contracts',
        'non_tax_allowance_deductions',
        'reminders',
        'salary_additions',
        'salary_advances',
        'salary_allowances',
        'salary_deductions',
        'salary_histories',
        'salary_periods',
        'shift_duration_checks',
        'shift_keys',
        'shift_schedules',
        'system_parameters',
        'tax_allowance_deductions',
        'time_recorder_logs',
        'transportation_allowances',
        'user_insurances',
        'user_monthly_salary_details',
        'user_overtimes',
        'user_permissions',
        'user_personal_income_tax_deductions',
        'user_status_histories',
        'user_work_hours',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            if (Schema::hasColumn($table, 'company_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('company_id')->nullable()->after('id');
            });

            // Backfill all existing rows to company_id = 1
            DB::table($table)->whereNull('company_id')->update(['company_id' => 1]);
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'company_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('company_id');
            });
        }
    }
};
