<?php

return [
    /*'admins' => [
        [
            'code'      => 'G01',
            'name'      => 'APPS',
            'display'   => true,
            'route'     => null,
            'is_deliver'=> true,
            'sub_menus' => [
            ]

        ],[
            'code'      => 'M01',
            'name'      => 'User List',
            'actives'   => ['admin.user.index', 'admin.user.add', 'admin.user.edit'],
            'display'   => true,
            'route'     => 'admin.user.index',
        ],
        [
            'code'      => 'M02',
            'name'      => 'Upload file',
            'actives'   => ['admin.file.index', 'admin.file.add', 'admin.file.edit'],
            'display'   => true,
            'route'     => 'admin.file.index',
        ],
    ],*/
    'hrms' => [
        [
            'code' => 'human_resource',
            'name' => 'Human Resource',
            'route_name' => null,
            'display' => true,
            'children' => [
                [
                    'code' => 'staff_salary',
                    'name' => 'Staff & Salary',
                    'route_name' => null,
                    'display' => 1,
                    'children' => [ // Level 2 - Submenus
//                        ['code' => 'organization_tree', 'name' => 'Organization Tree', 'route_name' => null, 'display' => 1],
//                        ['code' => 'organization_tree_terminated', 'name' => 'Organization Tree (Terminated)', 'route_name' => null, 'display' => 1],
                        ['code' => 'employees', 'name' => 'Employees', 'route_name' => 'admin.user.index', 'display' => 1],

                        ['code' => 'employees_terminated', 'name' => 'Employees (Terminated)', 'route_name' => 'admin.user.terminate', 'display' => 1],
                        ['code' => 'employees_import', 'name' => 'Import Employees', 'route_name' => 'admin.user.import', 'display' => 1],
//                        ['code' => 'export_employee', 'name' => 'Export Employee', 'route_name' => null, 'display' => 1],
//                        ['code' => 'export_employee_terminated', 'name' => 'Export Employee (Terminated)', 'route_name' => null, 'display' => 1],
//                        ['code' => 'export_dependant', 'name' => 'Export Dependant', 'route_name' => null, 'display' => 1],
//                        ['code' => 'import_labour_contract', 'name' => 'Import Labour Contract', 'route_name' => null, 'display' => 1],
//                        ['code' => 'statistics_direct_indirect', 'name' => 'Statistics Diret-InDiret', 'route_name' => null, 'display' => 1],
//                        ['code' => 'recruitment', 'name' => 'Recruitment', 'route_name' => null, 'display' => 1],
//                        ['code' => 'import_indirect', 'name' => 'Import InDirect', 'route_name' => null, 'display' => 1],
//                        ['code' => 'import_taxcode', 'name' => 'Import TaxCode', 'route_name' => null, 'display' => 1],
//                        ['code' => 'export_issue_uniform', 'name' => 'Export Issue Uniform', 'route_name' => null, 'display' => 1],
//                        ['code' => 'import_section', 'name' => 'Import Section', 'route_name' => null, 'display' => 1],
//                        ['code' => 'employee_action', 'name' => 'Employee Action', 'route_name' => null, 'display' => 1],
//                        ['code' => 'import_email', 'name' => 'Import Email', 'route_name' => null, 'display' => 1],
                    ]
                ],
                [
                    'code' => 'leave_manager',
                    'name' => 'Leave Management',
                    'route_name' => null,
                    'display' => 1,
                    'children' => [ // Level 2 - Submenus
                        ['code' => 'annual_leave_mgt', 'name' => 'Annual Leaves', 'route_name' => 'admin.annual_leave.index', 'display' => 1],
                        ['code' => 'monthly_leave_mgt', 'name' => 'Monthly Leaves', 'route_name' => 'admin.monthly_leave_balance.index', 'display' => 1],
                        ['code' => 'process_annual_leave_mgt', 'name' => 'Process Annual Leave', 'route_name' => 'admin.annual_leave.process', 'display' => 1],
                        ['code' => 'leave_mgt', 'name' => 'Manage Leaves', 'route_name' => 'admin.leave.index', 'display' => 1],
                        ['code' => 'leave_register_mgt', 'name' => 'Leave Register', 'route_name' => 'admin.leave.register', 'display' => 1],
                        ['code' => 'import_leave_mgt', 'name' => 'Import Leave', 'route_name' => 'admin.leave.import', 'display' => 1],

                    ]
                ],
                [
                    'code' => 'timesheet_management',
                    'name' => 'Timesheet Management',
                    'route_name' => null,
                    'display' => 1,
                    'children' => [
                        ['code' => 'import_shiftkey', 'name' => 'Import ShiftKey', 'route_name' => null, 'display' => 1],
                        ['code' => 'shiftkey_2_tabs', 'name' => 'ShiftKey (2 tabs)', 'route_name' => null, 'display' => 1],
                        ['code' => 'shift_assignment', 'name' => 'Shift Assignment', 'route_name' => null, 'display' => 1],
                        ['code' => 'check_locked_shift_assignment', 'name' => 'Check Locked ShiftAssignment', 'route_name' => null, 'display' => 1],
                        ['code' => 'detail_early_late_ot', 'name' => 'Detail In/Out-Early/Late-OT', 'route_name' => null, 'display' => 1],
                        ['code' => 'raw_log_downloader', 'name' => 'Time Recorder Raw Log Downloader', 'route_name' => null, 'display' => 1],
                        ['code' => 'cross_over_users', 'name' => 'Cross-Over Department Users', 'route_name' => null, 'display' => 1],
                        ['code' => 'cross_check', 'name' => 'Cross Check', 'route_name' => null, 'display' => 1],
                        ['code' => 'create_work_day', 'name' => 'Create Work Day', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_work_rate', 'name' => 'Import Work Rate', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_annual', 'name' => 'Export Annual', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_insurance_number', 'name' => 'Import Insurance Number', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_leave', 'name' => 'Import Leave', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_leaves', 'name' => 'Export Leaves', 'route_name' => null, 'display' => 1],
                        ['code' => 'control_leaves_days', 'name' => 'Control LeaveS Continuous Days', 'route_name' => null, 'display' => 1],
                        ['code' => 'holiday_manager', 'name' => 'Holiday Manager', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_overtime', 'name' => 'Import Overtime', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_employees_attendant', 'name' => 'Export Employees Attendant', 'route_name' => null, 'display' => 1],
                        ['code' => 'overtime_compare_result', 'name' => 'Overtime Compare Result', 'route_name' => null, 'display' => 1],
                        ['code' => 'check_locked_overtime', 'name' => 'Check Locked Overtime', 'route_name' => null, 'display' => 1],
                        ['code' => 'scheduled_working_day', 'name' => 'Scheduled Working Day', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_early_per_month', 'name' => 'Import Early Per Month', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_line', 'name' => 'Import Line', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_early_late', 'name' => 'Import Early Late', 'route_name' => null, 'display' => 1],
                    ]
                ],
                [
                    'code' => 'modification_approval',
                    'name' => 'Modification/Approval',
                    'route_name' => null,
                    'display' => 1,
                    'children' => [
                        ['code' => 'modify_attend', 'name' => 'Modify Attend', 'route_name' => null, 'display' => 1],
                        ['code' => 'modify_overtime', 'name' => 'Modify Overtime', 'route_name' => null, 'display' => 1],
                        ['code' => 'modify_leave', 'name' => 'Modify Leave', 'route_name' => null, 'display' => 1],
                        ['code' => 'modify_early_late', 'name' => 'Modify Early/Late', 'route_name' => null, 'display' => 1],
                        ['code' => 'time_recorder_raw_data', 'name' => 'Time Recorder Raw Data', 'route_name' => null, 'display' => 1],
                        ['code' => 'other_function', 'name' => 'Other Function', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_employees_timekeeper', 'name' => 'Export Employees Timekeeper', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_rate_working', 'name' => 'Export Rate Working', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_rate_working_new', 'name' => 'Export Rate Working New', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_labor_overall', 'name' => 'Export Labor Overall', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_salary_history_terminate', 'name' => 'Export Salary History Terminate', 'route_name' => null, 'display' => 1],
                    ]
                ],
                [
                    'code' => 'payroll',
                    'name' => 'Payroll',
                    'route_name' => null,
                    'display' => 1,
                    'children' => [
                        ['code' => 'lock_salary', 'name' => 'Lock Salary', 'route_name' => null, 'display' => 1],
                        ['code' => 'calculate_salary', 'name' => 'CalculateSalary', 'route_name' => 'admin.calculate_salary.index', 'display' => 1],
                        ['code' => 'payroll', 'name' => 'Payroll', 'route_name' => 'admin.payroll.index', 'display' => 1],
                        ['code' => 'payroll_security', 'name' => 'Payroll Security', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_salary_excel', 'name' => 'Export Salary From File Excel', 'route_name' => null, 'display' => 1],
                        ['code' => 'arrange_print', 'name' => 'Arrange For Print', 'route_name' => null, 'display' => 1],
                        ['code' => 'meal_report', 'name' => 'Meal Report', 'route_name' => null, 'display' => 1],
                        ['code' => 'multiple_meals', 'name' => 'Multiple Meals', 'route_name' => null, 'display' => 1],
                        ['code' => 'salary_histories', 'name' => 'Salary Histories', 'route_name' => null, 'display' => 1],
                        ['code' => 'salary_matrix', 'name' => 'Salary Matrix', 'route_name' => null, 'display' => 1],
                        ['code' => 'salary_factors', 'name' => 'Salary Factors', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_new_employee', 'name' => 'Import New Employee', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_card_id', 'name' => 'Update Card ID', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_employee_status', 'name' => 'Update Employee Status', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_salary_history', 'name' => 'Update Salary History', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_bank_account', 'name' => 'Update Bank Account Number', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_resignation_date', 'name' => 'Update Resignation Date', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_late_registration', 'name' => 'Update Late Registration', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_allowance_deduction', 'name' => 'Update Allowance and Deduction', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_harmful', 'name' => 'Update Harmful', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_union', 'name' => 'Update Union', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_child_care', 'name' => 'Update Child Care', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_ohs', 'name' => 'Update OHS', 'route_name' => null, 'display' => 1],
                        ['code' => 'update_skill', 'name' => 'Update Skill', 'route_name' => null, 'display' => 1],
                        ['code' => 'export_terminate_allowance', 'name' => 'Export Terminate Allowance', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_salary_scale', 'name' => 'Import Salary Scale', 'route_name' => null, 'display' => 1],
                        ['code' => 'salary_scale_editor', 'name' => 'Salary Scale Editor', 'route_name' => null, 'display' => 1],
                        ['code' => 'import_annual_remain', 'name' => 'Import Annual Remain', 'route_name' => null, 'display' => 1],
                        ['code' => 'tax_finalization', 'name' => 'Tax Finalization', 'route_name' => null, 'display' => 1],
                    ]
                ]
            ]
        ],
        [
            'code' => 'time_recorders',
            'name' => 'Time Recorders',
            'route_name' => null,
            'display' => true,
            'children' => [
                [
                    'code' => 'time_recorder_data',
                    'name' => 'TimeRecorder & Raw Data',
                    'route_name' => null,
                    'display' => true,
                    'children' => [
                        ['code' => 'time_recorder_settings', 'name' => 'TimeRecorder Settings', 'route_name' => null, 'display' => true],
                        ['code' => 'import_time_recorder_log', 'name' => 'Import TimeRecorder Log', 'route_name' => null, 'display' => true],
                    ]
                ]
            ]
        ],
        [
            'code' => 'system_settings',
            'name' => 'System Settings',
            'route_name' => null,
            'display' => true,
            'children' => [
                ['code' => 'company_info', 'name' => 'Company Info', 'route_name' => 'admin.company.index', 'display' => true],
                [
                    'code' => 'organization',
                    'name' => 'Organization',
                    'route_name' => null,
                    'display' => true,
                    'children' => [
                        ['code' => 'organization_department', 'name' => 'Departments', 'route_name' => 'admin.department.index', 'display' => true],
                        ['code' => 'organization_office', 'name' => 'Offices', 'route_name' => 'admin.office.index', 'display' => true],
                    ]
                ],
                [
                    'code' => 'settings',
                    'name' => 'Settings',
                    'route_name' => null,
                    'display' => true,
                    'children' => [
                        ['code' => 'setting_allowance_type', 'name' => 'Allowance Types', 'route_name' => 'admin.allowance_types.index', 'display' => true],
                        ['code' => 'setting_nation', 'name' => 'Nations', 'route_name' => 'admin.nation.index', 'display' => true],
                        ['code' => 'setting_skill', 'name' => 'Skills', 'route_name' => 'admin.skill.index', 'display' => true],
                        ['code' => 'setting_qualification', 'name' => 'Qualifications', 'route_name' => 'admin.qualification.index', 'display' => true],
                        ['code' => 'setting_contract_type', 'name' => 'Contract Types', 'route_name' => 'admin.contract_types.index', 'display' => true],
                        ['code' => 'setting_family_relationship', 'name' => 'Family Relations', 'route_name' => 'admin.family_relation.index', 'display' => true],
                        ['code' => 'setting_education', 'name' => 'Educations', 'route_name' => 'admin.education.index', 'display' => true],
                        ['code' => 'setting_level', 'name' => 'Levels', 'route_name' => 'admin.levels.index', 'display' => true],
                        ['code' => 'setting_user_status', 'name' => 'Employee Statuses', 'route_name' => 'admin.user_status.index', 'display' => true],
                        ['code' => 'setting_leave_category', 'name' => 'Leave Categories', 'route_name' => 'admin.leave_category.index', 'display' => true],
                        ['code' => 'setting_leave_type', 'name' => 'Leave Types', 'route_name' => 'admin.leave_type.index', 'display' => true],
                        ['code' => 'setting_overtime_type', 'name' => 'Overtime Types', 'route_name' => 'admin.overtime_types.index', 'display' => true],
                        ['code' => 'setting_work_shift', 'name' => 'Work Shifts', 'route_name' => 'admin.work_shifts.index', 'display' => true],

//                        ['code' => 'setting_grade', 'name' => 'Grade', 'route_name' => 'admin.grade.index', 'display' => true],
//                        ['code' => 'setting_transportation_type', 'name' => 'Transportation Types', 'route_name' => 'admin.transportation_type.index', 'display' => true],


//                        ['code' => 'setting_work_shift', 'name' => 'Work Shifts', 'route_name' => 'admin.work_shift.index', 'display' => true],
                    ]
                ],
                ['code' => 'parameter', 'name' => 'Parameter', 'route_name' => 'admin.parameter.index', 'display' => true],
                [
                    'code' => 'security',
                    'name' => 'Security',
                    'route_name' => null,
                    'display' => true,
                    'children' => [
                        ['code' => 'assign_access', 'name' => 'Assign Access Privileges Report', 'route_name' => null, 'display' => true],
                        ['code' => 'view_log', 'name' => 'View Log', 'route_name' => null, 'display' => true],
                    ]
                ],
                ['code' => 'sync_data', 'name' => 'Sync Data', 'route_name' => 'admin.sync-data.index', 'display' => true],
            ]
        ]

    ],

    'route_to_parent' => [
        // Human Resource routes
        'admin.user.index' => 'human_resource',
        'admin.user.terminate' => 'human_resource',
        'admin.user.import' => 'human_resource',
        'admin.annual_leave.index' => 'human_resource',
        'admin.monthly_leave_balance.index' => 'human_resource',
        'admin.annual_leave.process' => 'human_resource',
        'admin.leave.index' => 'human_resource',
        'admin.leave.register' => 'human_resource',
        'admin.leave.import' => 'human_resource',
        'admin.calculate_salary.index' => 'human_resource',
        'admin.payroll.index' => 'human_resource',

        // System Settings routes
        'admin.company.index' => 'system_settings',
        'admin.department.index' => 'system_settings',
        'admin.office.index' => 'system_settings',
        'admin.skill.index' => 'system_settings',
        'admin.qualification.index' => 'system_settings',
        'admin.family_relation.index' => 'system_settings',
        'admin.education.index' => 'system_settings',
        'admin.grade.index' => 'system_settings',
        'admin.user_status.index' => 'system_settings',
        'admin.transportation_type.index' => 'system_settings',
        'admin.leave_category.index' => 'system_settings',
        'admin.leave_type.index' => 'system_settings',
        'admin.work_shift.index' => 'system_settings',
        'admin.parameter.index' => 'system_settings',
    ],

];
