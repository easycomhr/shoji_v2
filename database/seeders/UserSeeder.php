<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
       // DB::table('users')->truncate();

        $this->command->info('Starting migration from tblusers to users...');

        // Lấy dữ liệu từ tblusers
        $tblUsersData = DB::table('tblusers')->get();

        if ($tblUsersData->isEmpty()) {
            $this->command->warn('No data found in tblusers table.');
            return;
        }

        $usersData = [];

        foreach ($tblUsersData as $tblUser) {
            $userData = $this->mapTblUserToUser($tblUser);
            if ($userData) {
                $usersData[] = $userData;
            }
        }

        // Insert batch data
        if (!empty($usersData)) {
            foreach (array_chunk($usersData, 50) as $chunk) {
                DB::table('users')->insert($chunk);
            }

            $this->command->info('Successfully migrated ' . count($usersData) . ' users from tblusers.');
        } else {
            $this->command->warn('No valid data to migrate.');
        }
    }

    /**
     * Map tblusers record to users table structure
     */
    private function mapTblUserToUser($tblUser): ?array
    {
        try {
            return [
                // Thông tin cơ bản
                'company_id' => 1, // Default company_id
                'code' => $tblUser->userid, // userid -> code
                'name' => $tblUser->realname,
                'avatar' => $tblUser->photopath,
                'picture_type' => $tblUser->picturetype,
                'picture_size' => $tblUser->picturesize ?? 0,
                'email' => $this->generateEmail($tblUser->realname, $tblUser->companyemail, $tblUser->privateemail),
                'password' => $tblUser->password ? $tblUser->password : Hash::make('password123'),

                // Thông tin quản lý - không có trong tblusers, để null
                'main_manager_code' => null,
                'sub_manager_code' => null,

                // Thông tin tổ chức
                'department_id' => $tblUser->departmentid,
                'division_id' => $tblUser->divisionid,
                'office_id' => $tblUser->officeid,
                'regional' => null, // không có trong tblusers
                'transportation_id' => null, // không có trong tblusers
                'group_id' => $tblUser->groupid,
                'group_action_id' => $tblUser->groupactionid,
                'tax_scheme_id' => null, // không có trong tblusers

                // Thông tin cá nhân
                'nickname' => $tblUser->nickname,
                'employee_code' => $tblUser->employeecode,
                'acc_code' => $tblUser->acc_code,
                'nationality' => $tblUser->nationality,
                'religion' => $tblUser->religion,
                'is_married' => $tblUser->married ?? 0,
                'gender' => $tblUser->sex ?? 1,
                'birthday' => $this->parseDate($tblUser->birthdate),
                'birth_place' => $tblUser->birthplace,

                // Thông tin giấy tờ
                'id_card' => $tblUser->idnumber,
                'id_card_issue_date' => $this->parseDate($tblUser->idissuedate),
                'id_card_issue_place' => $tblUser->idissueplace,
                'passport' => $tblUser->passportno,
                'passport_issue_date' => $this->parseDate($tblUser->passportissuedate),
                'passport_expiry_date' => $this->parseDate($tblUser->passportexprieddate),
                'passport_issue_place' => $tblUser->passportissueplace,

                // Thông tin công việc
                'timekeeper_card_id' => $tblUser->employeecardid,
                'contract_number' => null, // không có trong tblusers
                'contract_type_id' => null, // không có trong tblusers

                // Thông tin địa chỉ
                'home_address' => $tblUser->homeaddress,
                'temporary_address' => $tblUser->tempaddress,

                // Thông tin liên lạc
                'extension' => $tblUser->extension,
                'phone' => $tblUser->mobilephone,
                'home_phone' => $tblUser->homephone,
                'office_phone' => $tblUser->office_phone,
                'fax_number' => null, // không có trong tblusers
                'company_email' => $tblUser->companyemail,
                'private_email' => $tblUser->privateemail,

                // Thông tin tuyển dụng
                'join_date' => $this->parseDate($tblUser->joindate),
                'probation_period' => null, // không có trong tblusers
                'probation_period_unit' => null, // không có trong tblusers
                'probation_start' => $this->parseDate($tblUser->probationstart),
                'probation_end' => $this->parseDate($tblUser->probationend),
                'probation_salary_percentage' => null, // không có trong tblusers
                'seniority_date' => $this->parseDate($tblUser->senior_date),
                'termination_date' => $this->parseDate($tblUser->terminatedate),
                'termination_date_registered' => $this->parseDate($tblUser->terminatedatereg),
                'status_from_date' => $this->parseDate($tblUser->statusfromdate),

                // Thông tin thuế và tài chính
                'im_id' => null, // không có trong tblusers
                'tax_code' => $tblUser->taxcode,
                'bank_account_number' => $tblUser->bankaccountnumber,
                'bank_account_type' => null, // không có trong tblusers
                'bank_name' => $tblUser->bank,
                'bank_branch' => $tblUser->bankbranch,
                'bank_address' => null, // không có trong tblusers

                // Thông tin bảo hiểm
                'insurance_number' => $tblUser->insurancenumber,
                'health_insurance_number' => $tblUser->healthcareinsurancenumber,
                'social_insurance_date' => $this->parseDate($tblUser->sidate),
                'social_insurance_place' => $tblUser->siplace,

                // Cài đặt hệ thống
                'access_level' => $tblUser->fullaccess ?? 1,
                'last_visit_date' => $this->parseDateTime($tblUser->lastvisitdate),
                'page_size' => $tblUser->pagesize ?? 'A4',
                'modules_allowed' => $tblUser->modulesallowed,
                'monthly_timesheet' => null, // không có trong tblusers
                'is_blocked' => $tblUser->blocked ?? 0,
                'display_records_max' => $tblUser->displayrecordsmax ?? 0,
                'theme' => $tblUser->theme ?? 'theme_ultimate',
                'language' => $tblUser->language ?? 'en',
                'on_mouse_move' => $tblUser->onmousemove ?? 2,

                // Thông tin bổ sung
                'title' => null, // không có trong tblusers
                'is_system_user' => $tblUser->sys ?? 0,
                'overnight_shift_allowed' => null, // không có trong tblusers
                'is_union' => null, // không có trong tblusers
                'country_id' => $tblUser->countryid,
                'is_foreigner' => $tblUser->is_foreigner ?? 0,
                'is_office' => $tblUser->is_office ?? 0,
                'is_lunch_allow' => $tblUser->is_lunch_allow ?? 1,

                // Thông tin lương
                'first_basic_salary' => null, // không có trong tblusers
                'increase_rate' => null, // không có trong tblusers
                'total_contract_duration' => null, // không có trong tblusers

                // Cài đặt giao diện
                'menu_system' => $tblUser->menusystem ?? 'horizontal',
                'flag' => $tblUser->flag ?? 0,

                // Thông tin địa lý và khác
                'province_id' => null, // không có trong tblusers
                'is_direct' => null, // không có trong tblusers
                'comments' => null, // không có trong tblusers

                // Trạng thái và quyền
                'user_status_id' => $tblUser->userstatusid,
                'role' => null, // không có trong tblusers
                'is_login' => null, // không có trong tblusers
                'lasted_login' => $this->parseDateTime($tblUser->lastvisitdate),

                // Ghi chú
                'notes' => $tblUser->notes,
                'is_have_baby' => $tblUser->is_have_baby,

                // Timestamps
                'created_at' => $this->parseDateTime($tblUser->insertpointoftime) ?? now(),
                'updated_at' => now(),
            ];
        } catch (Exception $e) {
            $this->command->error('Error mapping user: ' . $tblUser->userid . ' - ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper method để parse date
     */
    private function parseDate($date)
    {
        if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Helper method để parse datetime
     */
    private function parseDateTime($datetime)
    {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return null;
        }

        try {
            return Carbon::parse($datetime);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Helper method để generate email từ name
     */
    private function generateEmail($name, $companyEmail = null, $privateEmail = null)
    {
        if ($companyEmail) {
            return $companyEmail;
        }

        if ($privateEmail) {
            return $privateEmail;
        }

        if (empty($name)) {
            return 'user@company.com';
        }

        // Generate email từ tên
        $emailName = $this->convertNameToEmail($name);
        return $emailName . '@company.com';
    }

    /**
     * Convert Vietnamese name to email format
     */
    private function convertNameToEmail($name)
    {
        $name = strtolower($name);

        // Remove Vietnamese accents
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        ];

        $noAccents = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];

        $name = str_replace($accents, $noAccents, $name);
        $name = preg_replace('/[^a-z0-9\s]/', '', $name);
        $name = str_replace(' ', '.', trim($name));

        return $name;
    }
}