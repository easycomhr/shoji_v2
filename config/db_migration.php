<?php

return [
    'tables' => [
        // STEP 1: sync users first — old userid stored in users.code for later lookups
        'users' => [
            'label'      => 'Nhân Viên',
            'old_table'  => 'tblusers',
            'new_table'  => 'users',
            'upsert_key' => 'code',   // users.code stores old tblusers.userid
            'column_map' => [
                'userid'                    => 'code',
                'realname'                  => 'name',
                'nickname'                  => 'nickname',
                'nationality'               => 'nationality',
                'religion'                  => 'religion',
                'married'                   => 'is_married',
                'sex'                       => 'gender',
                'birthdate'                 => 'birthday',
                'birthplace'                => 'birth_place',
                'idnumber'                  => 'id_card',
                'idissuedate'               => 'id_card_issue_date',
                'idissueplace'              => 'id_card_issue_place',
                'passportno'                => 'passport',
                'passportissuedate'         => 'passport_issue_date',
                'passportexprieddate'       => 'passport_expiry_date',
                'passportissueplace'        => 'passport_issue_place',
                'employeecardid'            => 'timekeeper_card_id',
                'employeecode'              => 'employee_code',
                'homeaddress'               => 'home_address',
                'tempaddress'               => 'temporary_address',
                'extension'                 => 'extension',
                'mobilephone'               => 'phone',
                'homephone'                 => 'home_phone',
                'office_phone'              => 'office_phone',
                'companyemail'              => 'company_email',
                'privateemail'              => 'private_email',
                'joindate'                  => 'join_date',
                'probationstart'            => 'probation_start',
                'probationend'              => 'probation_end',
                'permanent_date'            => 'seniority_date',
                'terminatedate'             => 'termination_date',
                'terminatedatereg'          => 'termination_date_registered',
                'userstatusid'              => 'user_status_id',
                'statusfromdate'            => 'status_from_date',
                'bankaccountnumber'         => 'bank_account_number',
                'bank'                      => 'bank_name',
                'bankbranch'                => 'bank_branch',
                'insurancenumber'           => 'insurance_number',
                'healthcareinsurancenumber' => 'health_insurance_number',
                'sidate'                    => 'social_insurance_date',
                'siplace'                   => 'social_insurance_place',
                'taxcode'                   => 'tax_code',
                'acc_code'                  => 'acc_code',
                'lastvisitdate'             => 'last_visit_date',
                'blocked'                   => 'is_blocked',
                'notes'                     => 'notes',
                'is_foreigner'              => 'is_foreigner',
                'is_office'                 => 'is_office',
                'is_lunch_allow'            => 'is_lunch_allow',
                'menusystem'                => 'menu_system',
                'flag'                      => 'flag',
                'countryid'                 => 'country_id',
            ],
            // email required: use companyemail, fallback privateemail, fallback generated
            'email_from' => ['companyemail', 'privateemail'],
        ],

        // STEP 2: sync insurance — lookup users.id by users.code (= old userid)
        'labour_contracts' => [
            'label'      => 'Labour Contracts',
            'old_table'  => 'tbllabourcontracts',
            'new_table'  => 'labour_contracts',
            'upsert_key' => 'user_id', // composite — handled in code
            'user_id_lookup' => [
                'old_field'    => 'userid',
                'lookup_field' => 'code',
            ],
            'column_map' => [
                'contracttypeid'   => 'contract_type_id',
                'contractno'       => 'contract_number',
                'contractstartday' => 'start_date',
                'contractendday'   => 'end_date',
                'comment'          => 'notes',
            ],
        ],

        'user_insurances' => [
            'label'      => 'Bảo Hiểm Nhân Viên',
            'old_table'  => 'tbluserinsurances',
            'new_table'  => 'user_insurances',
            'upsert_key' => 'user_id',
            // old 'userid' (e.g. "070201-HOATT") stored in users.code after users sync
            'user_id_lookup' => [
                'old_field'    => 'userid',   // tbluserinsurances.userid = old string code
                'lookup_field' => 'code',     // users.code = old userid string
            ],
            'column_map' => [
                // tbluserinsurances.insurancenumber → user_insurances.social_insurance_number
                'insurancenumber'           => 'social_insurance_number',
                // tbluserinsurances.sidate → user_insurances.social_insurance_start_date
                'sidate'                    => 'social_insurance_start_date',
                // tbluserinsurances.end_date → user_insurances.social_insurance_end_date
                'end_date'                  => 'social_insurance_end_date',
                // tbluserinsurances.si_place → user_insurances.social_insurance_place
                'si_place'                  => 'social_insurance_place',
                // tbluserinsurances.healthcareinsurancenumber → user_insurances.health_insurance_number
                'healthcareinsurancenumber' => 'health_insurance_number',
                // tbluserinsurances.healthplace → user_insurances.health_insurance_place
                'healthplace'               => 'health_insurance_place',
                // tbluserinsurances.is_locked → user_insurances.is_locked
                'is_locked'                 => 'is_locked',
            ],
        ],
    ],
];
