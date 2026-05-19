<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const USER_STATUS_ACTIVE_ID = 1;
    const USER_STATUS_TERMINATE_ID = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Thông tin cơ bản
        'company_id',
        'code',
        'name',
        'avatar',
        'picture_data',
        'picture_type',
        'picture_size',
        'email',
        'password',

        // Thông tin quản lý
        'main_manager_code',
        'sub_manager_code',

        // Thông tin tổ chức
        'department_id',
        'position_id',
        'division_id',
        'office_id',
        'regional',
        'transportation_id',
        'group_id',
        'group_action_id',
        'tax_scheme_id',

        // Thông tin cá nhân
        'nickname',
        'employee_code',
        'acc_code',
        'nationality',
        'religion',
        'is_married',
        'gender',
        'birthday',
        'birth_place',

        // Thông tin giấy tờ
        'id_card',
        'id_card_issue_date',
        'id_card_issue_place',
        'passport',
        'passport_issue_date',
        'passport_expiry_date',
        'passport_issue_place',

        // Thông tin công việc
        'timekeeper_card_id',
        'contract_number',
        'contract_type_id',

        // Thông tin địa chỉ
        'home_address',
        'temporary_address',

        // Thông tin liên lạc
        'extension',
        'phone',
        'home_phone',
        'office_phone',
        'fax_number',
        'company_email',
        'private_email',

        // Thông tin tuyển dụng
        'join_date',
        'probation_period',
        'probation_period_unit',
        'probation_start',
        'probation_end',
        'probation_salary_percentage',
        'seniority_date',
        'termination_date',
        'termination_date_registered',
        'status_from_date',

        // Thông tin thuế và tài chính
        'im_id',
        'tax_code',
        'accounting_code',
        'bank_account_number',
        'bank_account_type',
        'bank_name',
        'bank_branch',
        'bank_address',

        // Thông tin bảo hiểm
        'insurance_number',
        'health_insurance_number',
        'social_insurance_date',
        'social_insurance_place',

        // Cài đặt hệ thống
        'access_level',
        'last_visit_date',
        'page_size',
        'modules_allowed',
        'monthly_timesheet',
        'is_blocked',
        'display_records_max',
        'theme',
        'language',
        'on_mouse_move',

        // Thông tin bổ sung
        'title',
        'is_system_user',
        'overnight_shift_allowed',
        'is_union',
        'country_id',
        'is_foreigner',
        'is_office',
        'is_lunch_allow',

        // Thông tin lương
        'first_basic_salary',
        'increase_rate',
        'total_contract_duration',

        // Cài đặt giao diện
        'menu_system',
        'flag',

        // Thông tin địa lý và khác
        'province_id',
        'is_direct',
        'comments',

        // Trạng thái và quyền
        'user_status_id',
        'user_status_from_date',
        'terminate_date',
        'role',
        'is_login',
        'lasted_login',

        // Ghi chú
        'notes',
        'is_have_baby',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'picture_data', // Ẩn dữ liệu hình ảnh vì có thể rất lớn
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Timestamps
        'email_verified_at' => 'datetime',
        'last_visit_date' => 'datetime',
        'lasted_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

        // Dates
        'birthday' => 'date',
        'id_card_issue_date' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'join_date' => 'date',
        'probation_start' => 'date',
        'probation_end' => 'date',
        'seniority_date' => 'date',
        'termination_date' => 'date',
        'termination_date_registered' => 'date',
        'status_from_date' => 'date',
        'social_insurance_date' => 'date',
        'user_status_from_date' => 'date',

        // Boolean fields
        'is_married' => 'boolean',
        'monthly_timesheet' => 'boolean',
        'is_blocked' => 'boolean',
        'is_system_user' => 'boolean',
        'overnight_shift_allowed' => 'boolean',
        'is_union' => 'boolean',
        'is_foreigner' => 'boolean',
        'is_office' => 'boolean',
        'is_lunch_allow' => 'boolean',
        'is_have_baby' => 'boolean',

        // Numeric fields
        'probation_salary_percentage' => 'decimal:2',
        'first_basic_salary' => 'decimal:2',
        'increase_rate' => 'decimal:2',
        'total_contract_duration' => 'decimal:2',

        // Password hashing
        'password' => 'hashed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

//
//    public function division()
//    {
//        return $this->belongsTo(Division::class);
//    }
//
//    public function office()
//    {
//        return $this->belongsTo(Office::class);
//    }
//
//    public function group()
//    {
//        return $this->belongsTo(Group::class);
//    }
//
//    public function country()
//    {
//        return $this->belongsTo(Country::class);
//    }

    // Accessors & Mutators
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getPictureUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    public function getIsActiveAttribute()
    {
        return !$this->is_blocked && $this->is_login;
    }

    public function getCustomNameAttribute()
    {
        return $this->code .' - '.$this->name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_blocked', 0)->where('is_login', 1);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }

    public function scopeEmployees($query)
    {
        return $query->where('is_system_user', 0);
    }

    public function scopeSystemUsers($query)
    {
        return $query->where('is_system_user', 1);
    }

    public function getIsTerminateAttribute()
    {
        if (empty($this->terminate_date)) {
            return false;
        }

        return Carbon::parse($this->terminate_date)->lt(Carbon::today());
    }

    /**
     * Get the full path for user avatar
     *
     * @return string
     */
    public function getAvatarPathAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return asset('images/no_avatar.svg');
    }

    /**
     * Lịch sử trạng thái nhân viên
     */
    public function employeeStatuses()
    {
        return $this->hasMany(EmployeeStatus::class, 'user_id');
    }

    /**
     * Có phương tiện đi lại gì
     */
    public function transportationAllowance()
    {
        return $this->belongsTo(TransportationAllowance::class, 'transportation_id');
    }

    /**
     * Lịch sử lương của nhân viên
     */
    public function salaryHistories()
    {
        return $this->hasMany(SalaryHistory::class)->orderBy('applied_date', 'desc');
    }

    /**
     * Phụ cấp lương theo tháng
     */
    public function salaryAllowances()
    {
        return $this->hasMany(SalaryAllowance::class);
    }

    /**
     * Lịch ca làm việc theo tháng
     */
    public function shiftKeys()
    {
        return $this->hasMany(ShiftKey::class);
    }

    /**
     * Giờ làm việc thực tế
     */
    public function userWorkHours()
    {
        return $this->hasMany(UserWorkHour::class);
    }

    /**
     * Thông tin chấm công
     */
    public function shiftDurationChecks()
    {
        return $this->hasMany(ShiftDurationCheck::class);
    }

    /**
     * Nghỉ phép của nhân viên
     */
    public function userLeaves()
    {
        return $this->hasMany(UserLeave::class);
    }

    /**
     * Tăng ca của nhân viên
     */
    public function userOvertimes()
    {
        return $this->hasMany(UserOvertime::class);
    }

    /**
     * Lịch sử nhóm làm việc
     */
    public function groupHistories()
    {
        return $this->hasMany(GroupHistory::class);
    }

    /**
     * Lịch sử chức vụ
     */
    public function employeePositions()
    {
        return $this->hasMany(EmployeePosition::class);
    }

    /**
     * Thông tin giảm trừ thuế
     */
    public function personalIncomeTaxDeductions()
    {
        return $this->hasMany(UserPersonalIncomeTaxDeduction::class);
    }

    /**
     * Phụ cấp/khấu trừ chịu thuế
     */
    public function taxAllowanceDeductions()
    {
        return $this->hasMany(TaxAllowanceDeduction::class);
    }

    /**
     * Phụ cấp/khấu trừ không chịu thuế
     */
    public function nonTaxAllowanceDeductions()
    {
        return $this->hasMany(NonTaxAllowanceDeduction::class);
    }

    /**
     * Chi tiết lương tháng
     */
    public function monthlySalaryDetails()
    {
        return $this->hasMany(UserMonthlySalaryDetail::class);
    }

    // === SCOPES - Các phạm vi truy vấn ===

    /**
     * Lấy nhân viên hết thử việc
     */
    public function scopePassedProbation($query)
    {
        return $query->where('probation_end', '<', now())
            ->orWhereNull('probation_end');
    }

    // === ACCESSORS - Các thuộc tính tính toán ===

    /**
     * Lương hiện tại của nhân viên
     */
    public function getCurrentSalaryAttribute()
    {
        return $this->salaryHistories()->first();
    }

    /**
     * Nhóm làm việc hiện tại
     */
    public function getCurrentGroupAttribute()
    {
        return $this->groupHistories()
            ->whereNull('to_date')
            ->orWhere('to_date', '>=', now())
            ->with('group')
            ->first()?->group;
    }

    /**
     * Chức vụ hiện tại
     */
    public function getCurrentPositionAttribute()
    {
        return $this->employeePositions()
            ->whereNull('to_date')
            ->orWhere('to_date', '>=', now())
            ->first();
    }

    /**
     * Kiểm tra có đang trong thời gian thử việc không
     */
    public function getIsProbationAttribute()
    {
        return $this->probation_start &&
            (!$this->probation_end || $this->probation_end >= now());
    }

    public function insurances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserInsurance::class);
    }

    public function labourContracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LabourContract::class);
    }

    public function familyMembers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmployeeFamilyMember::class);
    }

    public function employeeAllowances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmployeeAllowance::class);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function finalTimesheets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FinalTimesheet::class);
    }

    public function salaryAdvances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalaryAdvance::class);
    }

    public function salaryDeductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalaryDeduction::class);
    }

    public function salaryAdditions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalaryAddition::class);
    }

    public function shiftSchedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShiftSchedule::class);
    }
}