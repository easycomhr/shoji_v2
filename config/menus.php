<?php

return [
    'hrms' => [
        // ─── Tab 1: HR Management ─────────────────────────────────────────────
        [
            'code'       => 'hr_management',
            'name'       => 'HR Management',
            'route_name' => null,
            'display'    => true,
            'children'   => [
                [
                    'code'       => 'employee_management',
                    'name'       => 'Employee Management',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'employees',              'name' => 'Employees List',          'route_name' => 'admin.user.index',    'display' => true],
                        ['code' => 'employees_terminated',   'name' => 'Terminated Employees',    'route_name' => 'admin.user.terminate','display' => true],
                        ['code' => 'employees_import',       'name' => 'Import New Employee',     'route_name' => 'admin.user.import',   'display' => true],
                        ['code' => 'insurance',              'name' => 'Insurance',               'route_name' => 'admin.insurance.index','display' => true],
                        ['code' => 'contract_management',    'name' => 'Contract Management',     'route_name' => 'admin.labour_contracts.index', 'display' => true],
                        ['code' => 'change_employee_code',   'name' => 'Change Employee Code',    'route_name' => 'admin.change_employee_code.index', 'display' => true],
                        ['code' => 'employee_email',         'name' => 'Employee Email',          'route_name' => null,                  'display' => true],
                        ['code' => 'login_history',          'name' => 'Login History',           'route_name' => null,                  'display' => true],
                        ['code' => 'import_team_history',    'name' => 'Import Team History',     'route_name' => null,                  'display' => true],
                    ],
                ],
                [
                    'code'       => 'hr_reports',
                    'name'       => 'HR Reports',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'export_employee_list',            'name' => 'Export Employee List',              'route_name' => null, 'display' => true],
                        ['code' => 'export_terminated_employees',     'name' => 'Export Terminated Employees',       'route_name' => null, 'display' => true],
                        ['code' => 'export_insurance_changes_monthly','name' => 'Export Insurance Changes Monthly',  'route_name' => null, 'display' => true],
                        ['code' => 'export_insurance_changes_yearly', 'name' => 'Export Insurance Changes Yearly',  'route_name' => null, 'display' => true],
                        ['code' => 'export_labour_contracts',         'name' => 'Export Labour Contracts',          'route_name' => null, 'display' => true],
                    ],
                ],
            ],
        ],

        // ─── Tab 2: Attendance Management ────────────────────────────────────
        [
            'code'       => 'attendance_management',
            'name'       => 'Attendance Management',
            'route_name' => null,
            'display'    => true,
            'children'   => [
                [
                    'code'       => 'attendance',
                    'name'       => 'Attendance',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'monthly_summary_timesheet',  'name' => 'Monthly Summary Timesheet',  'route_name' => null,                        'display' => true],
                        ['code' => 'modify_overtime',            'name' => 'Modify Overtime',            'route_name' => null,                        'display' => true],
                        ['code' => 'monthly_timesheet',          'name' => 'Monthly Timesheet',          'route_name' => null,                        'display' => true],
                        ['code' => 'modify_early_late',          'name' => 'Modify Early/Late',          'route_name' => null,                        'display' => true],
                        ['code' => 'modify_leave',               'name' => 'Modify Leave',               'route_name' => 'admin.leave.index',         'display' => true],
                        ['code' => 'reprocess_data',             'name' => 'Reprocess Data',             'route_name' => null,                        'display' => true],
                        ['code' => 'process_late_early_data',    'name' => 'Process Late/Early Data',    'route_name' => null,                        'display' => true],
                        ['code' => 'split_late_early_data',      'name' => 'Split Late/Early Data',      'route_name' => null,                        'display' => true],
                        ['code' => 'public_holidays',            'name' => 'Public Holidays',            'route_name' => null,                        'display' => true],
                        ['code' => 'annual_leave_management',    'name' => 'Annual Leave Management',    'route_name' => 'admin.annual_leave.index',  'display' => true],
                        ['code' => 'process_annual_leave',       'name' => 'Process Annual Leave',       'route_name' => null,                        'display' => true],
                        ['code' => 'special_leave_management',   'name' => 'Special Leave Management',   'route_name' => 'admin.leave_type.index',    'display' => true],
                        ['code' => 'split_leave_data',           'name' => 'Split Leave Data',           'route_name' => null,                        'display' => true],
                    ],
                ],
                [
                    'code'       => 'attendance_reports',
                    'name'       => 'Attendance Reports',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'overtime_report',          'name' => 'Overtime Report',          'route_name' => null, 'display' => true],
                        ['code' => 'late_early_report',        'name' => 'Late/Early Report',        'route_name' => null, 'display' => true],
                        ['code' => 'leave_information_report', 'name' => 'Leave Information Report', 'route_name' => null, 'display' => true],
                        ['code' => 'wrong_shift_report',       'name' => 'Wrong Shift Report',       'route_name' => null, 'display' => true],
                        ['code' => 'wrong_card_sweeping',      'name' => 'Wrong Card Sweeping',      'route_name' => null, 'display' => true],
                        ['code' => 'monthly_ta_summary',       'name' => 'Monthly TA Summary',       'route_name' => null, 'display' => true],
                        ['code' => 'annual_leave_report',      'name' => 'Annual Leave Report',      'route_name' => null, 'display' => true],
                    ],
                ],
                [
                    'code'       => 'time_recorder_data',
                    'name'       => 'Time Recorder Data',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'raw_time_recorder_data',           'name' => 'Raw Time Recorder Data',           'route_name' => null, 'display' => true],
                        ['code' => 'import_work_hours',                'name' => 'Import Work Hours',                'route_name' => null, 'display' => true],
                        ['code' => 'import_accumulated_leave_hours',   'name' => 'Import Accumulated Leave Hours',   'route_name' => null, 'display' => true],
                        ['code' => 'export_accumulated_leave_template','name' => 'Export Accumulated Leave Template','route_name' => null, 'display' => true],
                    ],
                ],
            ],
        ],

        // ─── Tab 3: Salary Management ─────────────────────────────────────────
        [
            'code'       => 'salary_management',
            'name'       => 'Salary Management',
            'route_name' => null,
            'display'    => true,
            'children'   => [
                [
                    'code'       => 'salary',
                    'name'       => 'Salary',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'salary_history',               'name' => 'Salary History',               'route_name' => null,                          'display' => true],
                        ['code' => 'import_salary_info',           'name' => 'Import Salary Info',           'route_name' => null,                          'display' => true],
                        ['code' => 'allowance_management',         'name' => 'Allowance Management',         'route_name' => 'admin.allowance_types.index', 'display' => true],
                        ['code' => 'work_summary',                 'name' => 'Work Summary',                 'route_name' => null,                          'display' => true],
                        ['code' => 'input_addition',               'name' => 'Input Addition',               'route_name' => null,                          'display' => true],
                        ['code' => 'input_deduction',              'name' => 'Input Deduction',              'route_name' => null,                          'display' => true],
                        ['code' => 'import_addition_deduction',    'name' => 'Import Addition/Deduction',    'route_name' => null,                          'display' => true],
                        ['code' => 'import_13th_month_salary_rate','name' => 'Import 13th Month Salary Rate','route_name' => null,                          'display' => true],
                        ['code' => 'import_advance',               'name' => 'Import Advance',               'route_name' => null,                          'display' => true],
                        ['code' => 'import_employee_allowances',   'name' => 'Import Employee Allowances',   'route_name' => null,                          'display' => true],
                        ['code' => 'payslip_email_history',        'name' => 'Payslip Email History',        'route_name' => null,                          'display' => true],
                        ['code' => 'send_payslip_email',           'name' => 'Send Payslip Email',           'route_name' => null,                          'display' => true],
                        ['code' => 'send_timesheet_email',         'name' => 'Send Timesheet Email',         'route_name' => null,                          'display' => true],
                        ['code' => 'manage_timesheet_email',       'name' => 'Manage Timesheet Email',       'route_name' => null,                          'display' => true],
                    ],
                ],
                [
                    'code'       => 'calculate_salary',
                    'name'       => 'Calculate Salary',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'calculate_salary_action', 'name' => 'Calculate Salary', 'route_name' => 'admin.calculate_salary.index', 'display' => true],
                    ],
                ],
                [
                    'code'       => 'salary_reports',
                    'name'       => 'Salary Reports',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'payroll',                  'name' => 'Payroll',                  'route_name' => 'admin.payroll.index', 'display' => true],
                        ['code' => 'export_salary_to_bank',    'name' => 'Export Salary to Bank',    'route_name' => null,                  'display' => true],
                        ['code' => 'export_tax_finalization',  'name' => 'Export Tax Finalization',  'route_name' => null,                  'display' => true],
                        ['code' => 'export_advance_to_bank',   'name' => 'Export Advance to Bank',   'route_name' => null,                  'display' => true],
                    ],
                ],
            ],
        ],

        // ─── Tab 4: System Settings ───────────────────────────────────────────
        [
            'code'       => 'system_settings',
            'name'       => 'System Settings',
            'route_name' => null,
            'display'    => true,
            'children'   => [
                [
                    'code'       => 'company_structure',
                    'name'       => 'Company Structure',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'organization', 'name' => 'Organization', 'route_name' => 'admin.department.index', 'display' => true],
                        ['code' => 'offices',      'name' => 'Offices',      'route_name' => 'admin.office.index',     'display' => true],
                    ],
                ],
                [
                    'code'       => 'system_config',
                    'name'       => 'System Configuration',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'company_info',        'name' => 'Company Info',        'route_name' => 'admin.company.index', 'display' => true],
                        ['code' => 'system_parameters',   'name' => 'System Parameters',   'route_name' => null,                  'display' => true],
                        ['code' => 'salary_parameters',   'name' => 'Salary Parameters',   'route_name' => null,                  'display' => true],
                        ['code' => 'sync_data',           'name' => 'Sync Data',           'route_name' => 'admin.sync-data.index','display' => true],
                        ['code' => 'db_migration',        'name' => 'DB Migration',        'route_name' => 'admin.db-migration.index', 'display' => true],
                    ],
                ],
                [
                    'code'       => 'security',
                    'name'       => 'Security',
                    'route_name' => null,
                    'display'    => true,
                    'children'   => [
                        ['code' => 'application_modules',   'name' => 'Application Modules',   'route_name' => null, 'display' => true],
                        ['code' => 'application_resources', 'name' => 'Application Resources', 'route_name' => null, 'display' => true],
                        ['code' => 'group_access',          'name' => 'Group Access',          'route_name' => null, 'display' => true],
                        ['code' => 'employee_access',       'name' => 'Employee Access',       'route_name' => null, 'display' => true],
                    ],
                ],
            ],
        ],
    ],

    'route_to_parent' => [
        // HR Management
        'admin.user.index'          => 'hr_management',
        'admin.user.terminate'      => 'hr_management',
        'admin.user.import'         => 'hr_management',
        'admin.insurance.index'     => 'hr_management',
        'admin.labour_contracts.index'       => 'hr_management',
        'admin.change_employee_code.index'   => 'hr_management',

        // Attendance Management
        'admin.leave.index'         => 'attendance_management',
        'admin.leave.register'      => 'attendance_management',
        'admin.leave.import'        => 'attendance_management',
        'admin.annual_leave.index'  => 'attendance_management',
        'admin.annual_leave.process'=> 'attendance_management',
        'admin.monthly_leave_balance.index' => 'attendance_management',
        'admin.leave_type.index'    => 'attendance_management',
        'admin.leave_category.index'=> 'attendance_management',

        // Salary Management
        'admin.allowance_types.index'     => 'salary_management',
        'admin.calculate_salary.index'    => 'salary_management',
        'admin.payroll.index'             => 'salary_management',

        // System Settings
        'admin.department.index'          => 'system_settings',
        'admin.office.index'              => 'system_settings',
        'admin.company.index'             => 'system_settings',
        'admin.sync-data.index'           => 'system_settings',
        'admin.parameter.index'           => 'system_settings',
        'admin.skill.index'               => 'system_settings',
        'admin.qualification.index'       => 'system_settings',
        'admin.family_relation.index'     => 'system_settings',
        'admin.education.index'           => 'system_settings',
        'admin.grade.index'               => 'system_settings',
        'admin.user_status.index'         => 'system_settings',
        'admin.transportation_type.index' => 'system_settings',
        'admin.nation.index'              => 'system_settings',
        'admin.levels.index'              => 'system_settings',
        'admin.overtime_types.index'      => 'system_settings',
        'admin.work_shifts.index'         => 'system_settings',
        'admin.contract_types.index'      => 'system_settings',
        'admin.leave_groups.index'        => 'system_settings',
        'admin.db-migration.index'        => 'system_settings',
    ],
];
