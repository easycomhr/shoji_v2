# Shoji HRIS — Tổng quan hệ thống & Hướng dẫn Migrate sang Laravel

> **Phiên bản tài liệu:** 1.0 | **Ngày:** 2026-03-22
> **Mục đích:** Tài liệu kiến trúc tham khảo cho dự án migration sang Laravel (Part 1)
> **Branch hiện tại:** `feature/#PO2026-06` (Split Leave feature)

---

## 1. Tổng quan hệ thống (System Overview)

### 1.1 Tech Stack

| Thành phần | Công nghệ hiện tại | Laravel Equivalent |
|---|---|---|
| **Backend** | PHP (no framework) | Laravel 11.x |
| **Database** | MySQL 8.x (port 3308) | MySQL 8.x + Eloquent ORM |
| **Frontend** | ExtJS 3.x + Prototype.js | Inertia.js + Vue 3 / Livewire |
| **Web Server** | Apache (XAMPP) | Nginx / Apache |
| **Session** | PHP native sessions | Laravel Session (file/redis) |
| **Auth** | SHA1 password hash | Laravel Auth + bcrypt/Argon2 |
| **Build** | Không có build step | Vite |
| **Deployment** | Windows XAMPP | Linux + Docker / Forge |

### 1.2 Mục đích hệ thống

Shoji HRIS là hệ thống quản lý nhân sự dành riêng cho công ty Shoji, bao gồm:

- **Quản lý nhân sự (HRM):** Hồ sơ nhân viên, hợp đồng, lịch sử công tác
- **Chấm công (Time & Attendance):** Tích hợp máy chấm công (MSSQL ZKAccess2022), xử lý bảng công
- **Nghỉ phép (Leave Management):** Phép năm, phép bù, phép không lương, tách phép (mới PO2026-06)
- **Tính lương (Payroll):** Lương tháng, lương tháng 13, phụ cấp, khấu trừ
- **Bảo hiểm (Insurance):** BHXH, BHYT
- **Báo cáo & Xuất file (Reports):** Excel, PDF cho các nghiệp vụ HR
- **Quản trị hệ thống:** Người dùng, phân quyền, cấu hình

### 1.3 Multi-Company Configuration

```php
// config.php
$DefaultCompany = 'shoji';  // Tên database MySQL
```

Bảng `tblcompanies` lưu danh sách công ty. Cột `coycode` là định danh công ty.
Toàn bộ dữ liệu trong một database duy nhất (single-tenant, không multi-tenant schema).

### 1.4 File & Directory Layout

```
shoji/
├── *.php              (~239 files) — Module pages & AJAX handlers
├── config.php         — Database & app configuration
├── constant.php       — Business constants
├── BaseService.php    — Static utility service
├── DBService.php      — DB abstraction (OOP wrapper)
├── Logger.php         — Application logger
├── GetMasterData.php  — JSON API (master data)
├── GetConfig.php      — tblconfig loader
├── includes/
│   ├── session.inc            — Bootstrap & auth
│   ├── ConnectDB_mysql.inc    — DB wrapper functions (DB_*)
│   ├── UtilFunctions.inc      — Utility functions (1000+ lines)
│   ├── DateFunctions.inc      — Date helpers
│   ├── Login.php              — Login form HTML
│   ├── xheader.inc / xfooter.inc — Page templates
│   └── LanguageSetup.php      — i18n setup
├── js/                (~102 .js files) — ExtJS frontend
├── css/               — Stylesheets & images
├── sql/               — Schema, stored procedures
│   ├── shoji.sql
│   ├── procedure.sql
│   └── fixed_onShiftKeyInput.sql
├── tmp/               — Upload & temp files
└── fonts/             — PDF fonts
```

---

## 2. Kiến trúc Request Lifecycle

### 2.1 Luồng xử lý request (Request Flow)

```
Browser Request
     │
     ▼
FeatureName.php  ──── include 'includes/session.inc' (FIRST LINE)
     │
     ▼
session.inc bootstrap (theo thứ tự):
  1. config.php          — DB config, app settings
  2. constant.php        — Business constants
  3. BaseService.php     — Static utility methods
  4. DBService.php       — OOP DB wrapper
  5. Logger.php          — Logging
  6. ConnectDB_mysql.inc — DB_* wrapper functions + DB connection
  7. UtilFunctions.inc   — Permission helpers, translate(), LogMe()
  8. GetConfig.php       — Load tblconfig & tblcompanies into $arrConfig
     │
     ▼
Auth Check: isset($_SESSION['AccessLevel']) ?
  ├─ NO  → Include Login.php → exit()
  └─ YES → Continue to page logic
     │
     ▼
Permission Check (optional per page):
  showErrorPermission($admin, $groupid, $filename)
     │
     ▼
Page renders HTML + ExtJS components
ExtJS makes AJAX calls to FeatureName_Data.php
     │
     ▼
FeatureName_Data.php:
  - include session.inc (same bootstrap)
  - Read $_POST / $_GET parameters
  - Execute business logic
  - Return JSON: {"success": true, "rows": [...]}
```

### 2.2 File Naming Convention

| Pattern | Mục đích | Ví dụ |
|---|---|---|
| `FeatureName.php` | UI page (HTML + ExtJS) | `UserLeaves.php` |
| `FeatureName_Data.php` | AJAX handler (JSON API) | `UserLeaves_Data.php` |
| `ExportXxx.php` | Export file (Excel/PDF) | `ExportTableSalaryFull.php` |
| `ImportXxx_Data.php` | Import handler | `ImportAllowance_Data.php` |
| `ProcessXxx.php` | Batch processing | `ProcessTimeSheet.php` |
| `ScheduledTasks_*.php` | Cron jobs | `ScheduledTasks_Annual.php` |

### 2.3 AJAX Response Format

```json
{
  "success": true,
  "rows": [
    { "field1": "value1", "field2": "value2" }
  ],
  "totalCount": 100
}
```

Lỗi trả về:
```json
{
  "success": false,
  "message": "Error description"
}
```

### 2.4 Laravel Equivalent

| Legacy Pattern | Laravel Equivalent |
|---|---|
| `FeatureName.php` | `Route::get() → Controller@index` + Blade/Inertia view |
| `FeatureName_Data.php` | `Route::post/get() → Controller@store/index` (API) |
| `include 'session.inc'` | Laravel middleware (auth, permission) |
| `showErrorPermission()` | `Gate::authorize()` / Policy |
| `$_POST['action']` switch | Separate named routes |

---

## 3. Database Access Pattern

### 3.1 DB_* Wrapper Functions (`includes/ConnectDB_mysql.inc`)

Toàn bộ code PHP sử dụng các wrapper functions này. **KHÔNG dùng mysqli trực tiếp.**

| Function | Tham số | Mục đích |
|---|---|---|
| `DB_query($sql, $db)` | SQL string, connection | Execute query, return result |
| `DB_fetch_array($result)` | Result resource | Fetch row as numeric array |
| `DB_fetch_assoc($result)` | Result resource | Fetch row as associative array |
| `DB_num_rows($result)` | Result resource | Get number of rows |
| `DB_free_result($result)` | Result resource | Free result memory |
| `DB_Last_Insert_ID($db)` | Connection | Get last inserted ID |
| `DB_escape_string($str)` | String | Escape string (deprecated) |
| `DB_Effected_row($db)` | Connection | Get affected rows count |
| `DB_error_msg($db)` | Connection | Get last error message |

**Migration concern:** `DB_escape_string()` dùng `mysql_real_escape_string()` đã deprecated. Cần chuyển sang Eloquent prepared statements.

### 3.2 DBService Class (`DBService.php`)

OOP wrapper đơn giản cho CRUD 1 bảng:

```php
// Legacy usage
DBService::table('tbluserleaves')->insert($data);
DBService::table('tbluserleaves')->update($data, $id);
DBService::table('tbluserleaves')->delete($id);
DBService::table('tbluserleaves')->findById($id);
DBService::table('tblusers')->updateByUserId($data, $userId);
```

**Laravel Equivalent:** Eloquent Model methods (`::create()`, `->update()`, `->delete()`, `::find()`)

### 3.3 BaseService Static Methods (`BaseService.php`)

| Method | Tham số | Mục đích |
|---|---|---|
| `convertDataToSql($data, $exclude)` | Array, excluded keys | Build SQL key=value string |
| `countWeekdays($from, $to)` | Date strings | Count business days between dates |
| `renderDataList($result, $key)` | DB result, key col | Deduplicate result by key |
| `convertResult($result)` | DB result | Convert to PHP array |
| `getList($sql, $db, $key)` | SQL, conn, key | Query + optional deduplicate |
| `convertKeyData($result, $key)` | Array, key col | Create assoc array |
| `first($sql, $db)` | SQL, conn | Get single row |
| `renderListKey($result, $key, $val)` | Array, key, val cols | Create key→value map |
| `reformatDate($date, $fromFmt, $toFmt)` | Date, formats | Convert date format |
| `renderDataForGrid($result, $total)` | Array, count | Paginate for ExtJS grid |
| `pluck($result, $col)` | Array, column | Extract column values |
| `pluckByKey($result, $keyCol)` | Array, key col | Group rows by key |
| `checkOfficeValid($officeId)` | Office ID | Validate office ID |
| `getAreaGroups($db)` | Connection | Get mst_area_groups list |
| `getAnnualRemain($db, $userId, $year)` | conn, user, year | Get remaining annual leave |

**Laravel Equivalent:** Eloquent Collections (pluck, groupBy, first), Repository pattern, Service classes.

### 3.4 Typical Query Pattern (Legacy vs Laravel)

**Legacy:**
```php
$sql = "SELECT * FROM tbluserleaves WHERE userid = '" . DB_escape_string($userId) . "'";
$result = DB_query($sql, $db);
while ($row = DB_fetch_assoc($result)) {
    $data[] = $row;
}
DB_free_result($result);
```

**Laravel:**
```php
$data = UserLeave::where('userid', $userId)->get();
```

---

## 4. Cấu trúc Module (Module Structure)

### 4.1 Bảng mapping Module → Files → Tables

| # | Module | PHP Files | Tables Chính |
|---|---|---|---|
| 1 | **Employee Management** | `Employee.php`, `EmployeeEditor.php`, `EmployeeWorkInfo_Data.php`, `EmployeeWorkHistory_Data.php`, `EmployeeLabourInfo_Data.php`, `EmployeeEducation_Data.php`, `EmployeeFamily_Data.php`, `EmployeeSecurity_Data.php`, `EmployeeSkill_Data.php`, `EmployeeQualification_Data.php`, `EmployeeLeaveInfo_Data.php` | `tblusers`, `tbldepartments`, `tbldivisions`, `tbloffices`, `tblpositions`, `tblworkhistories`, `tblemployeeeducations`, `tblemployeefamilies` |
| 2 | **Time & Attendance** | `TimeAttendantRecord.php`, `ImportTimeRecorderLog.php`, `ProcessTimeSheet.php`, `FinalProcessedTimesheet_Data.php` | `tbltimerecorderlog`, `tbluserworkhours`, `tblshiftdurationcheck` |
| 3 | **Shift Management** | `ShiftAssignmentbyDept.php`, `ShiftDurationCheck.php`, `ProcessShiftDurationCheck.php` | `tblworkshifts`, `tblshiftassignments`, `tblshiftdurationcheck` |
| 4 | **Leave Management** | `UserLeaves.php`, `UserLeaveByDept.php`, `ProcessUserLeave_Data.php`, `AnnualLeaveManager.php`, `AnnualLog.php`, `CompensationLeave.php`, `UserCompensationLeave.php`, **`SplitLeave.php`** (NEW) | `tbluserleaves`, `tblannualleaves`, `tblleavetypes`, `tblcompensationleaves` |
| 5 | **Accumulation Leave** | `AccumulationLeave.php`, `AccumulationLeaveManager.php`, `ExportAccumulationTemplate.php`, `ProcessAccumulation_Data.php`, `ImportAccumulation.php` | `tblaccumulationleaves`, `tblaccumulationleavetypes` |
| 6 | **Payroll Calculation** | `CalculateSalary.php`, `CalculateSalary13thMonth.php`, `SalaryHistories.php`, `SendMailPayslipManagement.php` | `tblsalaryhistories`, `tblsalarypayments`, `tblsalarydetails` |
| 7 | **Insurance Management** | `EmployeeInsuranceEditor.php`, `ExportInsMonth_Data.php`, `ExportInsYear_Data.php` | `tblemployeeinsurance`, `tblinsurancerates` |
| 8 | **Allowance Management** | `AllowanceManagement.php`, `AllowanceTypes_Data.php`, `InputAllowance_Data.php`, `ImportAllowance_Data.php` | `tblallowances`, `tblallowtypes` |
| 9 | **Advance & Deduction** | `InputDeductions_Data.php`, `ImportAdditionDeduction_Data.php`, `ImportAdvance_Data.php`, `ExportAdvanceToBank.php` | `tbldeductions`, `tbladvances` |
| 10 | **Overtime** | `OverTimeList_Data.php`, `ExportOTSummary_Data.php`, `ExportOverTimeInMonth_Data.php` | `tblovertimelist` |
| 11 | **Final Timesheet** | `FinalProcessedTimesheet_Data.php`, `ExportTableTimekeeper_Data.php` | `tblfinalprocessedtimesheets` |
| 12 | **Export / Reports** | `Export*.php` (~20 files) | (đọc nhiều bảng, không ghi) |
| 13 | **Import** | `Import*.php` (~10 files) | (ghi vào nhiều bảng) |
| 14 | **Organization** | `Organization_.php`, `CompanyInfo.php` | `tblcompanies`, `tbldepartments`, `tbldivisions`, `tbloffices`, `tblpositions` |
| 15 | **User & Permissions** | `Users.php`, `EmployeActionEditor.php`, `EmployeeActionGroup_Data.php`, `ApplicationEditor.php`, `ApplicationModule.php`, `ApplicationResource.php` | `tblusers`, `tblemployeeactions`, `tblemployeeactiongroups`, `tblapplicationresources`, `tblapplicationmodules`, `tblapplicationfunctiongroups`, `tblfunctionassignment` |
| 16 | **System Config** | `Settings.php`, `ApplicationParameter.php`, `AttendancePeriod.php`, `HolidayEditor_Data.php`, `ReminderEditor_Data.php` | `tblconfig`, `tblapplicationparameters`, `tblattendanceperiods`, `tblholidays`, `tblreminders` |
| 17 | **Scheduled Tasks** | `ScheduledTasks_Annual.php`, `ScheduledTasks_PerDay.php`, `ScheduledTasks_Reminder.php` | (orchestrate nhiều bảng) |
| 18 | **Holiday** | `HolidayEditor_Data.php` | `tblholidays` |

---

## 5. Authentication & Session

### 5.1 Auth Flow

```
POST Login.php
  │
  ├─ Input: userid, password
  │
  ├─ Query: SELECT * FROM tblusers
  │         WHERE userid = :userid
  │         AND password = SHA1(:password)
  │         AND blocked = 0
  │
  ├─ Special case: userid = '190401-NGANNTT' → force sys = 1
  │   (hardcoded admin override — REMOVE in Laravel)
  │
  ├─ On success:
  │   ├─ Set $_SESSION variables (xem 5.2)
  │   ├─ UPDATE tblusers SET lastvisitdate = NOW() WHERE userid = :userid
  │   ├─ Load permissions from tblemployeeactions
  │   ├─ INSERT tblauditlogin (userid, loginat, fromip)
  │   └─ INSERT login_histories (user_id, ip_address, device, created_at)
  │
  └─ On failure:
      └─ Redirect back to Login.php with error message
```

### 5.2 Session Variables

| `$_SESSION` Key | Nguồn (Source) | Mục đích |
|---|---|---|
| `UserID` | `tblusers.userid` | Employee ID hiện tại |
| `UserRealName` | `tblusers.realname` | Tên hiển thị |
| `AccessLevel` | Set on login | Flag "đã đăng nhập" (truthy = logged in) |
| `DepartID` | `tblusers.departmentid` | Department ID |
| `IsSysAdmin` | `tblusers.sys` | Super admin flag (0/1) |
| `groupAction` | `tblusers.groupactionid` | Permission group ID |
| `Theme` | `tblusers.theme` | UI theme preference |
| `Language` | `tblusers.lang` / config | Ngôn ngữ (vn/en) |
| `MenuSystem` | Built from tblemployeeactions | Danh sách module được phép |
| `OfficeID` | `tblusers.officeid` | Office ID của user |

### 5.3 Permission System

```
tblusers.groupactionid
    │
    └─► tblemployeeactiongroups (nhóm quyền)
             │
             └─► tblemployeeactions (chi tiết quyền)
                      │
                      └─► tblapplicationresources (file/page)
                                │
                                └─► tblapplicationfunctiongroups
                                         │
                                         └─► tblapplicationmodules
```

Permission levels (từ `getAuthorizationGroup()`):
- `"All Permission"` → ReadOnly=false, Approval=true (full access)
- `"Approval"` → ReadOnly=false, Approval=true
- `"Modify"` → ReadOnly=false, Approval=false
- `"Read Only"` → ReadOnly=true, Approval=false

### 5.4 Laravel Migration Strategy

```php
// Laravel: Auth → Gate/Policy
// Thay thế getAuthorizationGroup() bằng:

Gate::define('approve-leave', function (User $user) {
    return $user->hasPermission('Approval');
});

// Hoặc dùng Spatie Permission:
$user->givePermissionTo('leave.approve');
$user->assignRole('hr-manager');
```

---

## 6. Constants & Business Rules

### 6.1 Office IDs (`constant.php`)

| Constant | ID | Office |
|---|---|---|
| `$VTP_OFFICE_ID` | `7` | VTP (VSIP) |
| `$HN_OFFICE_ID` | `6` | Hà Nội |
| `$BD_OFFICE_ID` | `5` | Bình Dương (VSIP) |
| `$HCM_OFFICE_ID` | `4` | TP. Hồ Chí Minh |
| `$DL9_OFFICE_ID` | `8` | DL9 |

### 6.2 User Status IDs

| Constant | ID | Trạng thái |
|---|---|---|
| `$WORKING_STATUS_ID` | `8` | Đang làm việc |
| `$TERMINATE_STATUS_ID` | `9` | Đã nghỉ việc |
| `$MATERNITY_STATUS_ID` | `1` | Thai sản |
| `$AFTER_MATERNITY_STATUS_ID` | `2` | Sau thai sản |

### 6.3 Leave Type IDs

| Constant / Array | IDs | Loại phép |
|---|---|---|
| `$ARR_ANNUAL_LEAVE_TYPE` | `[1, 5, 14]` | Phép năm (trừ phép năm) |
| `$ARR_UNPAID_LEAVE_TYPE` | `[3, 4, 6]` | Phép không lương |
| `$ARR_UNCALC_MAX_LEAVE` | `[7,8,9,10,11,12,25]` | Không tính tối đa phép |
| `$ARR_LEAVE_LIMITED` | `[14, 15]` | Phép có giới hạn |
| `$ARR_VIOLATE_LEAVE_LIMITED` | `[2]` | Vi phạm giới hạn phép |
| `$UNEXPECTED_ANNUAL_LEAVE_ID` | `5` | Phép năm đột xuất |
| `$UNEXPECTED_LEAVE_ID` | `6` | Phép đột xuất (không lương) |

**`tblleavetypes.type_use` mapping:**

| `type_use` | Ý nghĩa | SplitLeave Label |
|---|---|---|
| `1` | Phép năm | Trừ vào phép năm |
| `2` | Phép khác | Trừ vào phép khác |
| `3` | Phép bù | Trừ vào phép bù |
| `4` | Phép chế độ | Trừ vào phép chế độ |
| `0` | Không có | Không có |

### 6.4 Shift IDs

| Office | `officeid` | Default Shift ID |
|---|---|---|
| TP. HCM | `4` | `1` |
| Bình Dương (VSIP) | `5` | `3` |
| VTP | `7` | `3` |
| Hà Nội | `6` | `2` |
| DL9 | `8` | `5` |

```php
$ARR_SHIFT_BY_OFFICE = [4 => 1, 5 => 3, 7 => 3, 6 => 2, 8 => 5];
$DRIVER_SHIFT_ID  = 4;
$DAY_OFF_SHIFT_ID = 0;
$HOLIDAY_SHIFT_ID = 10;
```

### 6.5 Position IDs Đặc biệt

| Constant | ID | Vị trí |
|---|---|---|
| `$CLEANER_POS_ID` | `21` | Tạp vụ |
| `$DRIVER_SG_POS_ID` | `20` | Tài xế (SG) |
| `$DRIVER_XT_POS_ID` | `22` | Tài xế (XT) |
| `$DRIVER_BD_POS_ID` | `23` | Tài xế (BD) |

### 6.6 Overtime Rule

```php
define('CAL_OT_AFTER_TIME', 15);  // Tính OT sau 15 phút
```

Nhân viên cần làm thêm ít nhất 15 phút sau giờ tan ca mới được tính OT.

### 6.7 Probation Contract

```php
$PROBATION_CONTRACT_ID = 3;  // ID hợp đồng thử việc
```

### 6.8 Admin Users (Hardcoded — REMOVE in Laravel)

```php
$arrayUserAdmin = ['admin', '190401-NGANNTT'];
```

### 6.9 Migrate Constants → Laravel

```php
// config/hris.php
return [
    'offices' => [
        'HCM' => 4,
        'BD'  => 5,
        'HN'  => 6,
        'VTP' => 7,
        'DL9' => 8,
    ],
    'user_status' => [
        'working'        => 8,
        'terminated'     => 9,
        'maternity'      => 1,
        'post_maternity' => 2,
    ],
    'leave_types' => [
        'annual'         => [1, 5, 14],
        'unpaid'         => [3, 4, 6],
        'no_max_calc'    => [7, 8, 9, 10, 11, 12, 25],
        'limited'        => [14, 15],
        'violate_limit'  => [2],
    ],
    'shift_by_office' => [4 => 1, 5 => 3, 7 => 3, 6 => 2, 8 => 5],
    'ot_after_minutes' => 15,
    'positions' => [
        'cleaner'    => 21,
        'driver_sg'  => 20,
        'driver_xt'  => 22,
        'driver_bd'  => 23,
    ],
];

// Usage:
config('hris.offices.HCM')  // → 4
```

---

## 7. Proposed Laravel Project Structure

```
app/
├── Models/
│   ├── User.php                    → tblusers
│   ├── Department.php              → tbldepartments
│   ├── Division.php                → tbldivisions
│   ├── Office.php                  → tbloffices
│   ├── Position.php                → tblpositions
│   ├── UserLeave.php               → tbluserleaves
│   ├── LeaveType.php               → tblleavetypes
│   ├── AnnualLeave.php             → tblannualleaves
│   ├── AccumulationLeave.php       → tblaccumulationleaves
│   ├── CompensationLeave.php       → tblcompensationleaves
│   ├── WorkShift.php               → tblworkshifts
│   ├── ShiftAssignment.php         → tblshiftassignments
│   ├── TimeRecorderLog.php         → tbltimerecorderlog
│   ├── UserWorkHour.php            → tbluserworkhours
│   ├── ShiftDurationCheck.php      → tblshiftdurationcheck
│   ├── OvertimeList.php            → tblovertimelist
│   ├── SalaryHistory.php           → tblsalaryhistories
│   ├── Allowance.php               → tblallowances
│   ├── AllowanceType.php           → tblallowtypes
│   ├── Deduction.php               → tbldeductions
│   ├── Advance.php                 → tbladvances
│   ├── EmployeeInsurance.php       → tblemployeeinsurance
│   ├── Holiday.php                 → tblholidays
│   ├── AttendancePeriod.php        → tblattendanceperiods
│   ├── ApplicationResource.php     → tblapplicationresources
│   ├── ApplicationModule.php       → tblapplicationmodules
│   ├── EmployeeAction.php          → tblemployeeactions
│   ├── EmployeeActionGroup.php     → tblemployeeactiongroups
│   ├── AuditLogin.php              → tblauditlogin
│   ├── LoginHistory.php            → login_histories
│   ├── SystemLog.php               → tblsystemlog
│   └── Config.php                  → tblconfig
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── LoginController.php
│   │   ├── EmployeeController.php
│   │   ├── LeaveController.php
│   │   ├── SplitLeaveController.php       ← NEW (PO2026-06)
│   │   ├── AccumulationLeaveController.php
│   │   ├── PayrollController.php
│   │   ├── TimesheetController.php
│   │   ├── AttendanceController.php
│   │   ├── OvertimeController.php
│   │   ├── InsuranceController.php
│   │   ├── AllowanceController.php
│   │   ├── OrganizationController.php
│   │   ├── ShiftController.php
│   │   ├── UserController.php
│   │   ├── ReportController.php
│   │   ├── ImportController.php
│   │   └── SystemConfigController.php
│   │
│   ├── Middleware/
│   │   ├── CheckPermission.php     → showErrorPermission()
│   │   └── CheckAttendanceLock.php → checkLockAttendancePeriod()
│   │
│   └── Requests/
│       ├── SplitLeaveRequest.php
│       ├── StoreLeaveRequest.php
│       └── CalculatePayrollRequest.php
│
├── Services/
│   ├── SalaryCalculationService.php   → CalculateSalary logic
│   ├── LeaveBalanceService.php        → CalculateAnnual() + getAnnualRemain()
│   ├── TimesheetProcessingService.php → ProcessTimeSheet logic
│   ├── OvertimeCalculationService.php → OT calculation (CAL_OT_AFTER_TIME)
│   ├── AttendanceImportService.php    → TimeRecorderLogScanner()
│   ├── SplitLeaveService.php          → SplitLeave_Data.php logic (NEW)
│   ├── PermissionService.php          → getAuthorizationGroup()
│   ├── ExportService.php              → Excel/PDF export helpers
│   └── NotificationService.php       → SendEmailService.php
│
├── Policies/
│   ├── LeavePolicy.php
│   ├── PayrollPolicy.php
│   └── EmployeePolicy.php
│
└── Console/
    └── Commands/
        ├── ProcessAnnualLeave.php    → ScheduledTasks_Annual.php
        ├── ProcessDailyTasks.php     → ScheduledTasks_PerDay.php
        └── SendReminders.php         → ScheduledTasks_Reminder.php

config/
├── hris.php          → constant.php (all business constants)
└── database.php      → config.php (DB settings)

database/
└── migrations/
    ├── create_tblusers_table.php
    ├── create_tbluserleaves_table.php
    ├── create_tblleavetypes_table.php
    ├── create_tblannualleaves_table.php
    ├── create_tblaccumulationleaves_table.php
    ├── create_tblworkshifts_table.php
    ├── create_tbltimerecorderlog_table.php
    ├── create_tbluserworkhours_table.php
    ├── create_tblovertimelist_table.php
    ├── create_tblsalaryhistories_table.php
    ├── create_tblallowances_table.php
    ├── create_tbldeductions_table.php
    ├── create_tblholidays_table.php
    ├── create_tblattendanceperiods_table.php
    ├── create_tblemployeeactions_table.php
    ├── create_tblauditlogin_table.php
    └── create_login_histories_table.php

routes/
├── web.php           → UI routes (Inertia / Blade)
└── api.php           → JSON API routes (thay _Data.php)

resources/
├── js/               → Vue 3 components (thay ExtJS)
│   ├── Pages/
│   │   ├── Leave/
│   │   │   ├── Index.vue
│   │   │   └── Split.vue    ← thay SplitLeave.php + SplitLeave.js
│   │   ├── Payroll/
│   │   └── Employee/
│   └── Components/
└── lang/
    ├── vn/            → tbllanguagetranslate (Vietnamese)
    └── en/            → tbllanguagetranslate (English)
```

---

## 8. Migration Checklist

### 8.1 Security

- [ ] **Password:** SHA1 → `Hash::make()` (bcrypt/Argon2)
  - Cần viết migration script: khi user login lần đầu, re-hash password
  - Xóa hardcoded password hash `e0d4179312a67fb4d0dca45621f9552ca2643458`
- [ ] **SQL Injection:** Thay string concatenation → Eloquent/Query Builder parameterized queries
- [ ] **Hardcoded credentials:** `TimeRecorderLogScanner()` chứa `sa/$SH1M@D@$` → dùng `.env`
- [ ] **Hardcoded admin:** `$arrayUserAdmin = ['admin', '190401-NGANNTT']` → dùng roles/permissions
- [ ] **File paths:** Log paths hardcoded cho Windows (`E:\xampp\...`) → dùng `storage_path()`

### 8.2 Database Schema

- [ ] **Foreign keys:** Thêm FK constraints (hiện tại chỉ enforce ở PHP level)
- [ ] **Soft deletes:** `terminatedate` trên `tblusers` → thêm `deleted_at` + `SoftDeletes` trait
- [ ] **Timestamps:** `insertpointoftime` → `created_at`; thêm cột `updated_at` cho tất cả bảng
- [ ] **Indexes:** Thêm composite indexes cho payroll batch queries (`userid + month + year`)
- [ ] **Charset:** Verify `utf8mb4` cho tất cả bảng (hỗ trợ emoji + đầy đủ Unicode)

### 8.3 Business Logic

- [ ] **Stored procedures:** `onShiftKeyInput` procedure → rewrite thành `ShiftService`
- [ ] **`CalculateAnnual()`:** Hàm tính phép năm phức tạp → `LeaveBalanceService`
- [ ] **`TimeRecorderLogScanner()`:** Kết nối MSSQL ngoài → dedicated `AttendanceImportService` với config trong `.env`
- [ ] **`ProcessTimeSheet` batch:** Logic xử lý bảng công → `TimesheetProcessingService` + queue job
- [ ] **Overtime calculation:** 15-minute rule → `OvertimeCalculationService`

### 8.4 Configuration & i18n

- [ ] **Constants → config files:** Toàn bộ `constant.php` → `config/hris.php`
- [ ] **DB config → `.env`:** `config.php` → `.env` + `config/database.php`
- [ ] **Translations:** `tbllanguagetranslate` → Laravel `lang/vn/*.php` + `lang/en/*.php`
- [ ] **App params:** `tblconfig` → `config/` files hoặc giữ DB với cache

### 8.5 Sessions & Auth

- [ ] **PHP sessions → Laravel session driver** (file hoặc Redis)
- [ ] **HTTPS enforcement:** Thêm `ForceHttps` middleware
- [ ] **Session lifetime:** `$SessionLifeTime = 3600` → `SESSION_LIFETIME=60` trong `.env`
- [ ] **Auth scaffolding:** Dùng Laravel Breeze hoặc Fortify

### 8.6 Permissions

- [ ] **`tblemployeeactions` → Laravel Gates/Policies** hoặc **Spatie Permission package**
  - Map từng `applicationresourceid` → named permission
  - Map `groupactionid` → roles
  - `ReadOnly` / `Approval` levels → gate checks

### 8.7 Frontend

- [ ] **ExtJS 3.x → Vue 3** (với Inertia.js) hoặc **Livewire**
  - ExtJS Grid → Vue DataTable component
  - ExtJS Form → Vue Form component
  - ExtJS Store (AJAX) → Axios/fetch + Pinia store
- [ ] **`GetMasterData.php` → API Resource routes** (`/api/master/leave-types`, etc.)
- [ ] **Menu system:** `VerticalTreeViewMenu` → Sidebar component với dynamic routes

### 8.8 Scheduled Tasks

- [ ] **`ScheduledTasks_Annual.php`** → `php artisan schedule:run` + `ProcessAnnualLeave` command
- [ ] **`ScheduledTasks_PerDay.php`** → `ProcessDailyTasks` command (chạy mỗi ngày)
- [ ] **`ScheduledTasks_Reminder.php`** → `SendReminders` command + Mail queues

### 8.9 Queue & Performance

- [ ] **Salary calculation batch** → Laravel Queue (Redis/database driver)
- [ ] **Excel/PDF export** → Queue jobs (tránh timeout)
- [ ] **Time recorder import** → Queue job (MSSQL → MySQL sync)

### 8.10 Testing

- [ ] **Unit tests:** `SalaryCalculationService`, `LeaveBalanceService`, `OvertimeCalculationService`
- [ ] **Feature tests:** Leave request flow, Payroll calculation, Auth flow
- [ ] **Database migrations test:** Verify FK constraints và data integrity sau migration

---

## 9. Database Schema Highlights

### 9.1 Bảng tblusers (Core)

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `userid` | VARCHAR PK | Employee ID (e.g., `190401-NGANNTT`) |
| `realname` | VARCHAR | Tên đầy đủ |
| `departmentid` | INT FK | → `tbldepartments` |
| `divisionid` | INT FK | → `tbldivisions` |
| `officeid` | INT FK | → `tbloffices` |
| `groupid` | INT | Group ID |
| `groupactionid` | INT FK | → `tblemployeeactiongroups` |
| `password` | VARCHAR | SHA1 hash |
| `hiredate` | DATE | Ngày vào làm |
| `terminatedate` | DATE | Ngày nghỉ việc (thay bằng `deleted_at`) |
| `sys` | TINYINT | Super admin flag |
| `blocked` | TINYINT | Tài khoản bị khóa |
| `lastvisitdate` | DATETIME | Lần đăng nhập cuối |
| `theme` | VARCHAR | UI theme |
| `lang` | VARCHAR | Ngôn ngữ (`vn`/`en`) |
| `modulesallowed` | TEXT | CSV danh sách module |

### 9.2 Bảng tbluserleaves (Leave Records)

| Cột | Kiểu | Ghi chú |
|---|---|---|
| `userleaveid` | INT PK AUTO | Leave record ID |
| `userid` | VARCHAR FK | → `tblusers` |
| `leavedate` | DATE | Ngày nghỉ |
| `leavetypeid` | INT FK | → `tblleavetypes` |
| `leaveamount` | DECIMAL | Số giờ/ngày nghỉ |
| `is_early` | TINYINT | Về sớm |
| `is_late` | TINYINT | Đi trễ |
| `approved` | TINYINT | 0=chờ duyệt, 1=đã duyệt |
| `comment` | TEXT | Ghi chú |
| `sysmarker` | VARCHAR | System marker |
| `register_date` | DATETIME | Ngày đăng ký |

### 9.3 External Database (Time Recorder)

```php
// Kết nối MSSQL trong TimeRecorderLogScanner()
Server:   SRV-MAYCHAMCONG\SQLEXPRESS,50807
Database: ZKAccess2022
Tables:   [CHECKINOUT], [USERINFO]
```

**Laravel migration:** Dùng `config/database.php` với connection `timeclock`:
```php
'timeclock' => [
    'driver'   => 'sqlsrv',
    'host'     => env('TIMECLOCK_HOST'),
    'database' => env('TIMECLOCK_DB'),
    'username' => env('TIMECLOCK_USER'),
    'password' => env('TIMECLOCK_PASS'),
],
```

---

## 10. Feature mới: Split Leave (PO2026-06)

### 10.1 Tổng quan

Tính năng cho phép tách 1 bản ghi nghỉ phép (`tbluserleaves`) thành nhiều bản ghi với các loại phép khác nhau.

**Use case:** Nhân viên xin nghỉ 8 tiếng phép năm, sau đó cần điều chỉnh thành 4 tiếng phép năm + 4 tiếng phép không lương.

### 10.2 Files liên quan

| File | Vai trò |
|---|---|
| `SplitLeave.php` | UI page (form display) |
| `SplitLeave_Data.php` | AJAX handler (business logic) |
| `js/SplitLeave.js` | ExtJS frontend (form + validation) |
| `css/images/ext/split.png` | Icon |

### 10.3 Workflow

```
GET SplitLeave.php?id={userleaveid}
  │
  ├─ Query tbluserleaves WHERE userleaveid = :id
  ├─ Display: Employee, Date, Leave Type, Amount (read-only)
  └─ Display: Input fields for each leave type (split targets)
       │
       ▼
User inputs split amounts → Click "Tách"
       │
POST SplitLeave_Data.php
  │
  ├─ Validate: sum(splits) <= original_amount
  ├─ Validate: at least one split > 0
  ├─ Check: checkLockAttendancePeriod(month, year)
  │
  ├─ remaining = original_amount - sum(splits)
  │
  ├─ IF remaining > 0:
  │     UPDATE tbluserleaves SET leaveamount = remaining WHERE userleaveid = :id
  │   IF remaining == 0:
  │     DELETE FROM tbluserleaves WHERE userleaveid = :id
  │
  ├─ INSERT INTO tbluserleaves (new records for each split type)
  │
  └─ Call CalculateAnnual() → recalculate annual leave balance
```

### 10.4 Laravel Migration cho SplitLeave

```php
// SplitLeaveController.php
public function split(SplitLeaveRequest $request, UserLeave $leave): JsonResponse
{
    Gate::authorize('split', $leave);
    return $this->splitLeaveService->split($leave, $request->validated());
}

// SplitLeaveService.php
public function split(UserLeave $original, array $splits): JsonResponse
{
    $this->checkAttendanceLock($original->leavedate);
    DB::transaction(function () use ($original, $splits) {
        $remaining = $original->leaveamount - collect($splits)->sum('amount');
        $remaining > 0
            ? $original->update(['leaveamount' => $remaining])
            : $original->delete();
        foreach ($splits as $split) {
            UserLeave::create([...$original->toArray(), ...$split]);
        }
        $this->leaveBalanceService->recalculate($original->userid, $original->leavedate);
    });
}
```

---

*Tài liệu này là Part 1 — System Architecture Overview. Part 2 bên dưới bao gồm: chi tiết schema migration với field mapping đầy đủ.*

---

## 9. Database Schema — Ánh xạ sang Laravel (Complete Field Mapping)

> Convention đặt tên cột Laravel: snake_case tiếng Anh, thêm comment tiếng Việt
> Tất cả bảng thêm: id (PK), created_at, updated_at, deleted_at (nếu cần soft delete)

### 9.1 tblusers → users
Laravel table: `users`
| Cột cũ (tblusers) | Cột mới (users) | Kiểu dữ liệu | Ghi chú (Tiếng Việt) |
|---|---|---|---|
| userid | id | bigIncrements | Mã nhân viên |
| realname | full_name | string(100) | Họ và tên |
| departmentid | department_id | foreignId | Mã phòng ban |
| officeid | office_id | foreignId | Mã văn phòng/chi nhánh |
| groupid | group_id | foreignId | Mã nhóm quyền |
| groupactionid | group_action_id | foreignId | Mã nhóm thao tác |
| birthdate | date_of_birth | date | Ngày sinh |
| idnumber | identity_number | string(20) | Số CMND/CCCD |
| hiredate | hire_date | date | Ngày tuyển dụng |
| joindate | join_date | date | Ngày vào làm chính thức |
| probationstart | probation_start_date | date | Ngày bắt đầu thử việc |
| probationend | probation_end_date | date | Ngày kết thúc thử việc |
| terminatedate | terminated_at | date nullable | Ngày nghỉ việc (soft delete reference) |
| employeecardid | employee_card_id | string(20) | Mã thẻ chấm công |
| photopath | photo_path | string nullable | Đường dẫn ảnh đại diện |
| picture | picture | string nullable | Ảnh nhân viên (binary/path) |
| bankaccountnumber | bank_account_number | string(30) nullable | Số tài khoản ngân hàng |
| insurancenumber | social_insurance_number | string(20) nullable | Số bảo hiểm xã hội |
| healthcareinsurancenumber | health_insurance_number | string(20) nullable | Số bảo hiểm y tế |
| sidate | social_insurance_start_date | date nullable | Ngày tham gia BHXH |
| fullaccess | is_full_access | boolean default false | Quyền truy cập đầy đủ |
| theme | ui_theme | string(20) nullable | Giao diện người dùng |
| language | ui_language | string(10) default 'vi' | Ngôn ngữ giao diện |
| sys | is_system_admin | boolean default false | Cờ quản trị hệ thống |
| blocked | is_blocked | boolean default false | Tài khoản bị khóa |
| password | password | string | Mật khẩu (bcrypt thay SHA1) |
| lastvisitdate | last_visited_at | timestamp nullable | Lần đăng nhập cuối |
| managerid | manager_id | foreignId nullable | Mã quản lý trực tiếp |
| menusystem | menu_system | string nullable | Loại menu hiển thị |
| displayrecordsmax | display_records_max | integer default 50 | Số bản ghi hiển thị/trang |
| userstatus | status_id | foreignId | Mã trạng thái (đang làm/nghỉ...) |

### 9.2 tblemployeepositions → employee_positions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| employeepositionid | id | bigIncrements | Mã bản ghi chức vụ |
| userid | user_id | foreignId | Mã nhân viên |
| companypositionid | company_position_id | foreignId | Mã chức danh |
| fromdatetime | start_date | date | Ngày bắt đầu chức vụ |
| todatetime | end_date | date nullable | Ngày kết thúc chức vụ |
| mainposition | is_main_position | boolean | Chức vụ chính |
| salary | base_salary | decimal(15,2) | Mức lương cơ bản |

### 9.3 tblcompanypositions → company_positions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| companypositionid | id | bigIncrements | Mã chức danh |
| companypositionname | position_name | string(100) | Tên chức danh |
| description | description | text nullable | Mô tả chức danh |

### 9.4 tbldepartments → departments
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| departmentid | id | bigIncrements | Mã phòng ban |
| departmentname | department_name | string(100) | Tên phòng ban |
| divisionid | division_id | foreignId nullable | Mã bộ phận/khối |
| officeid | office_id | foreignId nullable | Mã văn phòng |

### 9.5 tbldivisions → divisions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| divisionid | id | bigIncrements | Mã bộ phận/khối |
| divisionname | division_name | string(100) | Tên bộ phận |

### 9.6 tbloffices → offices
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| officeid | id | bigIncrements | Mã văn phòng/chi nhánh |
| officename | office_name | string(100) | Tên văn phòng |

### 9.7 tbluserleaves → user_leaves
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userleaveid | id | bigIncrements | Mã đơn nghỉ phép |
| userid | user_id | foreignId | Mã nhân viên |
| register_date | registered_at | date | Ngày đăng ký nghỉ |
| leavedate | leave_date | date | Ngày nghỉ |
| leavetypeid | leave_type_id | foreignId | Loại nghỉ phép |
| leaveamount | leave_amount | decimal(4,1) | Số ngày nghỉ |
| is_early | is_early_leave | boolean | Nghỉ sớm |
| is_late | is_late_leave | boolean | Nghỉ trễ |
| comment | note | text nullable | Ghi chú lý do nghỉ |
| approved | approval_status | tinyint | Trạng thái duyệt (0=chờ, 1=đã duyệt, -1=từ chối) |
| approvedby | approved_by | foreignId nullable | Người duyệt |

### 9.8 tblannualleaves → annual_leaves
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| annualleaveid | id | bigIncrements | Mã tồn phép năm |
| userid | user_id | foreignId | Mã nhân viên |
| ofyear | of_year | year | Năm tính phép |
| annualtransfer | transferred_days | decimal(5,1) | Số ngày phép chuyển từ năm trước |
| clear_transfer | cleared_transfer_days | decimal(5,1) | Số ngày phép chuyển đã xóa |
| annual_remain | remaining_days | decimal(5,1) | Số ngày phép còn lại |
| annualleave | allocated_days | decimal(5,1) | Số ngày phép được cấp |
| total_annual | total_days | decimal(5,1) | Tổng ngày phép trong năm |
| terminatedate | terminated_at | date nullable | Ngày nghỉ việc (dùng cho tính toán) |
| actualbalance | actual_balance | decimal(5,1) | Số dư thực tế |
| ispaid | is_paid | boolean | Đã thanh toán phép dư |
| is_transfer | is_transferred | boolean | Đã chuyển phép sang năm sau |
| is_clear | is_cleared | boolean | Đã xóa phép chuyển |
| monthpaid | month_paid | tinyint nullable | Tháng thanh toán phép |

### 9.9 tblleavetypes → leave_types
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| leavetypeid | id | bigIncrements | Mã loại nghỉ phép |
| leavetype | leave_type_name | string(100) | Tên loại nghỉ phép |
| color | color_hex | string(7) nullable | Màu hiển thị trên lịch |
| comment | description | text nullable | Mô tả loại nghỉ |
| allowmultiple | allow_multiple_per_day | boolean | Cho phép nhiều đơn trong 1 ngày |
| maxdayspertake | max_days_per_request | decimal(4,1) nullable | Số ngày tối đa 1 lần xin |
| maxdayspermonth | max_days_per_month | decimal(4,1) nullable | Số ngày tối đa trong tháng |
| maxdaysperquarter | max_days_per_quarter | decimal(4,1) nullable | Số ngày tối đa trong quý |
| maxdaysperannually | max_days_per_year | decimal(5,1) nullable | Số ngày tối đa trong năm |

### 9.10 tblworkshifts → work_shifts
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| shiftid | id | bigIncrements | Mã ca làm việc |
| shiftname | shift_name | string(50) | Tên ca |
| workstart | work_start_time | time | Giờ bắt đầu ca |
| workend | work_end_time | time | Giờ kết thúc ca |
| nightshift | is_night_shift | boolean | Ca đêm |
| dayoff | is_day_off | boolean | Ngày nghỉ |
| lunch_start | lunch_break_start | time nullable | Giờ bắt đầu nghỉ trưa |
| lunch_end | lunch_break_end | time nullable | Giờ kết thúc nghỉ trưa |
| kip | shift_cycle_days | integer default 1 | Chu kỳ ca (ngày) |
| defaultotearlyid | default_ot_early_type_id | foreignId nullable | Loại OT sớm mặc định |
| defaultotlateid | default_ot_late_type_id | foreignId nullable | Loại OT muộn mặc định |
| color | color_hex | string(7) nullable | Màu hiển thị |
| active | is_active | boolean default true | Ca đang hoạt động |

### 9.11 tbltimerecorderlog → time_recorder_logs
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| ondate | record_date | date | Ngày chấm công |
| employeecardid | employee_card_id | string(20) | Mã thẻ chấm công |
| direction | direction | tinyint | Hướng (1=vào, 2=ra) |
| atdatetime | recorded_at | datetime | Thời điểm chấm |
| userid | user_id | foreignId nullable | Mã nhân viên (resolve từ card) |

### 9.12 tbluserworkhours → user_work_hours
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| dateworked | work_date | date | Ngày làm việc |
| fromdatetime | check_in_time | datetime nullable | Giờ vào (tính toán) |
| fromdatetime_byrecorder | check_in_raw_time | datetime nullable | Giờ vào từ máy chấm công |
| todatetime | check_out_time | datetime nullable | Giờ ra (tính toán) |
| todatetime_byrecorder | check_out_raw_time | datetime nullable | Giờ ra từ máy chấm công |
| shiftid | shift_id | foreignId | Ca làm việc |
| admintype | admin_type | tinyint | Loại điều chỉnh quản lý |

### 9.13 tblovertimelist → overtime_records
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| otdate | ot_date | date | Ngày làm thêm giờ |
| overtimeearly | ot_early_minutes | integer default 0 | Số phút OT đầu ca |
| overtimeearlytypeid | ot_early_type_id | foreignId nullable | Loại OT đầu ca |
| overtimelate | ot_late_minutes | integer default 0 | Số phút OT cuối ca |
| overtimelatetypeid | ot_late_type_id | foreignId nullable | Loại OT cuối ca |
| totalovertime | total_ot_minutes | integer default 0 | Tổng số phút OT |
| shiftid | shift_id | foreignId | Ca làm việc |
| comment | note | text nullable | Ghi chú |
| autoupdatable | is_auto_updatable | boolean default true | Cho phép cập nhật tự động |

### 9.14 tblovertime → overtime_types
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| overtimeid | id | bigIncrements | Mã loại tăng ca |
| overtimename | overtime_type_name | string(100) | Tên loại tăng ca |
| fromtime | applicable_start_time | time | Giờ bắt đầu áp dụng |
| totime | applicable_end_time | time | Giờ kết thúc áp dụng |
| offtime | off_time_minutes | integer | Thời gian nghỉ (phút) |
| otvalueinpercent | ot_rate_percent | decimal(5,2) | Hệ số tăng ca (%) |
| defaultflag | is_default | boolean | Loại tăng ca mặc định |
| active | is_active | boolean | Đang sử dụng |
| comment | description | text nullable | Mô tả |

### 9.15 tblsalaryperiods → salary_periods
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| salaryperiodid | id | bigIncrements | Mã kỳ lương |
| salaryperiodname | period_name | string(100) | Tên kỳ lương (vd: Tháng 1/2025) |
| fromdate | period_start_date | date | Ngày bắt đầu kỳ lương |
| todate | period_end_date | date | Ngày kết thúc kỳ lương |
| standardworkingday | standard_working_days | decimal(4,1) | Số ngày công chuẩn |
| is_month13 | is_thirteenth_month | boolean default false | Kỳ lương tháng 13 |
| is_lock | is_locked | boolean default false | Đã khóa kỳ lương |

### 9.16 tbldetailusersalarybymonthhistories → salary_details
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| salaryperiodid | salary_period_id | foreignId | Mã kỳ lương |
| actualdaywork | actual_work_days | decimal(5,2) | Số ngày công thực tế |
| basicsalary | basic_salary | decimal(15,2) | Lương cơ bản |
| grosssalary | gross_salary | decimal(15,2) | Lương gộp (trước khấu trừ) |
| insurancesalary | insurance_salary | decimal(15,2) | Lương đóng bảo hiểm |
| hinsurance | health_insurance_amount | decimal(15,2) | Số tiền BHYT nhân viên đóng |
| sinsurance | social_insurance_amount | decimal(15,2) | Số tiền BHXH nhân viên đóng |
| uinsurance | unemployment_insurance_amount | decimal(15,2) | Số tiền BHTN nhân viên đóng |
| taxableamount | taxable_income | decimal(15,2) | Thu nhập chịu thuế |
| pitamount | personal_income_tax | decimal(15,2) | Thuế thu nhập cá nhân |
| totaldeduction | total_deductions | decimal(15,2) | Tổng khấu trừ |
| netsalary | net_salary | decimal(15,2) | Lương thực nhận |
| otamount | overtime_amount | decimal(15,2) | Tiền tăng ca |
| advanceamount | advance_deduction | decimal(15,2) | Khấu trừ tạm ứng |
| allowanceamount | total_allowances | decimal(15,2) | Tổng phụ cấp |
| leavedays | paid_leave_days | decimal(4,1) | Số ngày nghỉ có phép |
| unpaidleavedays | unpaid_leave_days | decimal(4,1) | Số ngày nghỉ không phép |

### 9.17 tbluserinsurances → user_insurances
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userinsuranceid | id | bigIncrements | Mã bảo hiểm nhân viên |
| userid | user_id | foreignId | Mã nhân viên |
| insurancenumber | social_insurance_number | string(20) | Số BHXH |
| sidate | social_insurance_start_date | date nullable | Ngày tham gia BHXH |
| siplace | social_insurance_place | string(200) nullable | Nơi đăng ký BHXH |
| healthcareinsurancenumber | health_insurance_number | string(20) nullable | Số BHYT |
| healthplace | health_insurance_place | string(200) nullable | Nơi đăng ký khám bệnh |
| end_date | end_date | date nullable | Ngày kết thúc bảo hiểm |
| is_locked | is_locked | boolean default false | Đã khóa bản ghi |

### 9.18 tblsalaryhistory → salary_histories
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| applieddate | effective_date | date | Ngày áp dụng mức lương |
| salary | salary_amount | decimal(15,2) | Mức lương |
| h_insurance_included | health_insurance_included | boolean | Lương đã gộp BHYT |
| s_insurance_included | social_insurance_included | boolean | Lương đã gộp BHXH |
| unemp_insurance_included | unemployment_insurance_included | boolean | Lương đã gộp BHTN |

### 9.19 tblparameters → system_parameters
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| parameterid | id | bigIncrements | Mã tham số |
| confname | parameter_key | string(100) | Tên tham số (Max of SI, PIT, ...) |
| confvalue | parameter_value | string(200) | Giá trị tham số |
| applieddate | effective_date | date | Ngày áp dụng |
| comment | description | text nullable | Mô tả tham số |

### 9.20 tblallowancetypes → allowance_types
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| allowancetypeid | id | bigIncrements | Mã loại phụ cấp |
| allowancetype | allowance_type_name | string(100) | Tên loại phụ cấp |
| is_ins | is_insurance_applicable | boolean | Tính vào lương đóng BH |
| comment | description | text nullable | Mô tả |

### 9.21 tblallowanceemployee → employee_allowances
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (join table) | id | bigIncrements | |
| userid | user_id | foreignId | Mã nhân viên |
| allowancetypeid | allowance_type_id | foreignId | Mã loại phụ cấp |
| amount | amount | decimal(15,2) | Số tiền phụ cấp |
| fromdate | start_date | date | Ngày bắt đầu |
| todate | end_date | date nullable | Ngày kết thúc |

### 9.22 tblholidays → holidays
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| holidayid | id | bigIncrements | Mã ngày lễ |
| holidaydate | holiday_date | date | Ngày lễ |
| holidayname | holiday_name | string(200) | Tên ngày lễ |
| officeid | office_id | foreignId nullable | Áp dụng cho văn phòng (null=tất cả) |

### 9.23 tblgroups → groups
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| groupid | id | bigIncrements | Mã nhóm |
| groupname | group_name | string(100) | Tên nhóm quyền |

### 9.24 tblapplicationmodules → application_modules
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| applicationmoduleid | id | bigIncrements | Mã module ứng dụng |
| applicationmodulename | module_name | string(100) | Tên module |

### 9.25 tblapplicationresources → application_resources (permissions)
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| applicationresourceid | id | bigIncrements | Mã quyền |
| applicationresourcename | resource_name | string(100) | Tên quyền/tính năng |

### 9.26 tblemployeeactions → user_permissions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| applicationresourceid | resource_id | foreignId | Mã quyền |
| (pivot) | | | Xem xét migrate sang Spatie Permission |

### 9.27 tblauditlogin → login_audit_logs
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| loginat | logged_in_at | timestamp | Thời điểm đăng nhập |
| fromip | ip_address | string(45) | Địa chỉ IP |

### 9.28 tbllabourcontracts → labour_contracts
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| labourcontractid | id | bigIncrements | Mã hợp đồng lao động |
| userid | user_id | foreignId | Mã nhân viên |
| contracttypeid | contract_type_id | foreignId | Loại hợp đồng |
| fromdate | start_date | date | Ngày ký hợp đồng |
| todate | end_date | date nullable | Ngày kết thúc hợp đồng |
| signdate | signed_date | date nullable | Ngày ký thực tế |

### 9.29 tblcontracttypes → contract_types
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| contracttypeid | id | bigIncrements | Mã loại hợp đồng |
| contracttype | contract_type_name | string(100) | Tên loại (Thử việc, Chính thức...) |

### 9.30 tbladvance → salary_advances
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| advanceid | id | bigIncrements | Mã tạm ứng |
| userid | user_id | foreignId | Mã nhân viên |
| salaryperiodid | salary_period_id | foreignId | Kỳ lương |
| amount | advance_amount | decimal(15,2) | Số tiền tạm ứng |
| comment | note | text nullable | Ghi chú |

### 9.31 tblinputdeductions → salary_deductions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (id) | id | bigIncrements | Mã khấu trừ |
| userid | user_id | foreignId | Mã nhân viên |
| salaryperiodid | salary_period_id | foreignId | Kỳ lương |
| amount | deduction_amount | decimal(15,2) | Số tiền khấu trừ |
| comment | note | text nullable | Ghi chú lý do khấu trừ |

### 9.32 tblinputaddition → salary_additions
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (id) | id | bigIncrements | Mã khoản cộng thêm |
| userid | user_id | foreignId | Mã nhân viên |
| salaryperiodid | salary_period_id | foreignId | Kỳ lương |
| amount | addition_amount | decimal(15,2) | Số tiền cộng thêm |
| comment | note | text nullable | Ghi chú |

### 9.33 tblfamilyinfo → employee_family_members
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| familyinfoid | id | bigIncrements | Mã thành viên gia đình |
| userid | user_id | foreignId | Mã nhân viên |
| surname | last_name | string(50) | Họ |
| firstname | first_name | string(50) | Tên |
| relationship | relationship | string(50) | Quan hệ (vợ/chồng, con...) |
| age | age | tinyint unsigned nullable | Tuổi |
| profession | profession | string(100) nullable | Nghề nghiệp |
| place | residence_place | string(200) nullable | Nơi cư trú |

### 9.34 tblcompanies → companies
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| companyid | id | bigIncrements | Mã công ty |
| companyname | company_name | string(200) | Tên công ty |
| companyshort | company_short_name | string(20) | Tên viết tắt |
| taxcode | tax_code | string(20) nullable | Mã số thuế |
| address | address | text nullable | Địa chỉ |

### 9.35 tblconfig → system_configs
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| configid | id | bigIncrements | Mã cấu hình |
| confname | config_key | string(100) | Tên cấu hình |
| confvalue | config_value | text | Giá trị cấu hình |
| companyid | company_id | foreignId | Mã công ty |

### 9.36 tbllanguagetranslate → language_translations
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| langcode | lang_code | string(100) | Mã ngôn ngữ (vd: FULL_NAME) |
| vi | vi_text | text | Văn bản tiếng Việt |
| en | en_text | text | Văn bản tiếng Anh |

### 9.37 tblfinaltimesheet → final_timesheets
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userid | user_id | foreignId | Mã nhân viên |
| salaryperiodid | salary_period_id | foreignId | Kỳ lương |
| workingdays | working_days | decimal(5,2) | Số ngày công thực tế |
| leavedays | paid_leave_days | decimal(4,1) | Ngày nghỉ có phép |
| unpaidleavedays | unpaid_leave_days | decimal(4,1) | Ngày nghỉ không phép |
| totalotminutes | total_ot_minutes | integer | Tổng phút tăng ca |
| lateearlyminutes | late_early_minutes | integer | Tổng phút đến trễ/về sớm |

### 9.38 tbl_annual_by_month → annual_leave_by_month
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| ofyear | of_year | year | Năm |
| ofmonth | of_month | tinyint | Tháng |
| userid | user_id | foreignId | Mã nhân viên |
| allowance_id | allowance_id | foreignId | Mã phụ cấp/ngày phép tháng |
| allowance_amount | allowance_amount | decimal(5,2) | Số ngày phép tháng |

### 9.39 tblaccumulationleave → accumulation_leaves
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (id) | id | bigIncrements | Mã tích lũy phép |
| userid | user_id | foreignId | Mã nhân viên |
| ofyear | of_year | year | Năm tích lũy |
| days | accumulated_days | decimal(5,2) | Số ngày phép tích lũy |

### 9.40 tbluserstatus → user_statuses
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| userstatusid | id | bigIncrements | Mã trạng thái |
| userstatus | status_name | string(100) | Tên trạng thái (Đang làm, Nghỉ việc...) |

### 9.41 tbluserstatushistory → user_status_histories
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (id) | id | bigIncrements | Mã bản ghi lịch sử |
| userid | user_id | foreignId | Mã nhân viên |
| userstatusid | user_status_id | foreignId | Mã trạng thái |
| fromdate | start_date | date | Ngày bắt đầu trạng thái |
| todate | end_date | date nullable | Ngày kết thúc trạng thái |
| comment | note | text nullable | Ghi chú |

### 9.42 tblreminder → reminders
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| reminderid | id | bigIncrements | Mã nhắc nhở |
| remindertype | reminder_type | string(50) | Loại nhắc nhở |
| userid | user_id | foreignId | Mã nhân viên liên quan |
| reminderdate | reminder_date | date | Ngày nhắc nhở |
| content | content | text | Nội dung nhắc nhở |
| issent | is_sent | boolean default false | Đã gửi |

### 9.43 tblschedules → shift_schedules
| Cột cũ | Cột mới | Kiểu | Ghi chú |
|---|---|---|---|
| (id) | id | bigIncrements | Mã lịch ca |
| userid | user_id | foreignId | Mã nhân viên |
| shiftid | shift_id | foreignId | Mã ca làm việc |
| scheduledate | schedule_date | date | Ngày phân ca |
| departmentid | department_id | foreignId nullable | Mã phòng ban |

---

## 10. Laravel Eloquent Relationships Summary

```php
// User model
class User extends Model {
    public function department(): BelongsTo // → departments
    public function office(): BelongsTo      // → offices
    public function positions(): HasMany     // → employee_positions
    public function currentPosition(): HasOne
    public function leaves(): HasMany        // → user_leaves
    public function annualLeave(): HasOne    // → annual_leaves (current year)
    public function workHours(): HasMany     // → user_work_hours
    public function overtimeRecords(): HasMany // → overtime_records
    public function salaryDetails(): HasMany  // → salary_details
    public function insurance(): HasOne       // → user_insurances
    public function contracts(): HasMany      // → labour_contracts
    public function familyMembers(): HasMany  // → employee_family_members
    public function manager(): BelongsTo     // → users (self-referential)
    public function subordinates(): HasMany  // → users (self-referential)
}
```

---

## 11. Laravel Migration Order (dependency-aware)

```
1.  companies
2.  offices, divisions
3.  departments (depends: offices, divisions)
4.  groups
5.  user_statuses
6.  users (depends: departments, offices, groups, user_statuses)
7.  company_positions
8.  employee_positions (depends: users, company_positions)
9.  leave_types
10. user_leaves (depends: users, leave_types)
11. annual_leaves (depends: users)
12. work_shifts, overtime_types
13. time_recorder_logs (depends: users)
14. user_work_hours (depends: users, work_shifts)
15. overtime_records (depends: users, work_shifts, overtime_types)
16. salary_periods
17. final_timesheets (depends: users, salary_periods)
18. salary_histories (depends: users)
19. allowance_types
20. employee_allowances (depends: users, allowance_types)
21. system_parameters
22. salary_details (depends: users, salary_periods)
23. user_insurances (depends: users)
24. salary_advances, salary_deductions, salary_additions (depends: users, salary_periods)
25. holidays (depends: offices)
26. contract_types
27. labour_contracts (depends: users, contract_types)
28. employee_family_members (depends: users)
29. application_modules, application_resources
30. user_permissions (depends: users, application_resources)
31. login_audit_logs (depends: users)
32. language_translations
33. system_configs (depends: companies)
34. reminders (depends: users)
35. shift_schedules (depends: users, work_shifts)
36. annual_leave_by_month (depends: users)
37. accumulation_leaves (depends: users)
38. user_status_histories (depends: users, user_statuses)
```

---

*Tài liệu Part 2 — Complete Database Schema with Laravel Migration Mapping. Ngày cập nhật: 2026-03-22.*

---

## 10. DB Migration Config — Ánh xạ thực tế (config/db_migration.php)

> **Mục đích:** Tài liệu chính xác dựa trên `config/db_migration.php` — cấu hình được `DbMigrationService` sử dụng để đồng bộ dữ liệu từ DB cũ sang DB mới.

### Tổng quan hai Database

| Thông số | DB Cũ (Shoji) | DB Mới (Shoji v2) |
|---|---|---|
| **Tên DB** | `shoji` | `shoji_v2` |
| **Port** | `3308` | `3307` |
| **Engine** | MySQL 8.x (legacy) | MySQL 8.x + Laravel Eloquent |
| **Encoding** | utf8/latin1 (mixed) | utf8mb4 |
| **Framework** | PHP no-framework | Laravel 11.x |

### Thứ tự đồng bộ (Sync Order)

Các bảng phải được đồng bộ **theo thứ tự** để đảm bảo FK constraint:

1. **users** — Đồng bộ trước tiên; `users.code` lưu `tblusers.userid` cũ để tra cứu sau
2. **user_insurances** — Phụ thuộc `users.id` (tra cứu qua `users.code`)

---

### 10.1 Nhân Viên

| | |
|---|---|
| **Old table** | `tblusers` (shoji:3308) |
| **New table** | `users` (shoji_v2:3307) |
| **Upsert key** | `code` — `users.code` lưu giá trị `tblusers.userid` cũ |
| **Email logic** | Ưu tiên `companyemail`; fallback `privateemail`; fallback tự sinh nếu cả hai rỗng |

| Cột cũ (tblusers) | Cột mới (users) | Ghi chú |
|---|---|---|
| `userid` | `code` | Mã nhân viên cũ — dùng làm upsert key & tra cứu FK |
| `realname` | `name` | Họ và tên đầy đủ |
| `nickname` | `nickname` | Tên thường gọi |
| `nationality` | `nationality` | Quốc tịch |
| `religion` | `religion` | Tôn giáo |
| `married` | `is_married` | Tình trạng hôn nhân |
| `sex` | `gender` | Giới tính |
| `birthdate` | `birthday` | Ngày sinh |
| `birthplace` | `birth_place` | Nơi sinh |
| `idnumber` | `id_card` | Số CMND / CCCD |
| `idissuedate` | `id_card_issue_date` | Ngày cấp CMND/CCCD |
| `idissueplace` | `id_card_issue_place` | Nơi cấp CMND/CCCD |
| `passportno` | `passport` | Số hộ chiếu |
| `passportissuedate` | `passport_issue_date` | Ngày cấp hộ chiếu |
| `passportexprieddate` | `passport_expiry_date` | Ngày hết hạn hộ chiếu |
| `passportissueplace` | `passport_issue_place` | Nơi cấp hộ chiếu |
| `employeecardid` | `timekeeper_card_id` | Mã thẻ máy chấm công |
| `employeecode` | `employee_code` | Mã nhân viên nội bộ |
| `homeaddress` | `home_address` | Địa chỉ thường trú |
| `tempaddress` | `temporary_address` | Địa chỉ tạm trú |
| `extension` | `extension` | Số máy lẻ nội bộ |
| `mobilephone` | `phone` | Số điện thoại di động |
| `homephone` | `home_phone` | Số điện thoại nhà |
| `office_phone` | `office_phone` | Số điện thoại văn phòng |
| `companyemail` | `company_email` | Email công ty *(email_from ưu tiên 1)* |
| `privateemail` | `private_email` | Email cá nhân *(email_from fallback 2)* |
| `joindate` | `join_date` | Ngày vào làm chính thức |
| `probationstart` | `probation_start` | Ngày bắt đầu thử việc |
| `probationend` | `probation_end` | Ngày kết thúc thử việc |
| `permanent_date` | `seniority_date` | Ngày tính thâm niên |
| `terminatedate` | `termination_date` | Ngày nghỉ việc |
| `terminatedatereg` | `termination_date_registered` | Ngày đăng ký nghỉ việc |
| `userstatusid` | `user_status_id` | Mã trạng thái nhân viên (FK) |
| `statusfromdate` | `status_from_date` | Ngày bắt đầu trạng thái hiện tại |
| `bankaccountnumber` | `bank_account_number` | Số tài khoản ngân hàng |
| `bank` | `bank_name` | Tên ngân hàng |
| `bankbranch` | `bank_branch` | Chi nhánh ngân hàng |
| `insurancenumber` | `insurance_number` | Số sổ BHXH |
| `healthcareinsurancenumber` | `health_insurance_number` | Số thẻ BHYT |
| `sidate` | `social_insurance_date` | Ngày tham gia BHXH |
| `siplace` | `social_insurance_place` | Nơi tham gia BHXH |
| `taxcode` | `tax_code` | Mã số thuế cá nhân |
| `acc_code` | `acc_code` | Mã kế toán |
| `lastvisitdate` | `last_visit_date` | Lần đăng nhập cuối |
| `blocked` | `is_blocked` | Tài khoản bị khóa (0/1) |
| `notes` | `notes` | Ghi chú |
| `is_foreigner` | `is_foreigner` | Là người nước ngoài (0/1) |
| `is_office` | `is_office` | Làm việc tại văn phòng (0/1) |
| `is_lunch_allow` | `is_lunch_allow` | Được phép ăn trưa công ty (0/1) |
| `menusystem` | `menu_system` | Loại menu hệ thống |
| `flag` | `flag` | Cờ nội bộ |
| `countryid` | `country_id` | Mã quốc gia (FK) |

---

### 10.2 Bảo Hiểm Nhân Viên

| | |
|---|---|
| **Old table** | `tbluserinsurances` (shoji:3308) |
| **New table** | `user_insurances` (shoji_v2:3307) |
| **Upsert key** | `user_id` |
| **user_id_lookup** | `tbluserinsurances.userid` → tra cứu `users.id` qua `users.code` (= old userid) |

> **Lưu ý:** Cột `userid` trong bảng cũ **không** map trực tiếp sang cột mới.
> `DbMigrationService` tra cứu `users.id` bằng cách tìm `users.code = old.userid` sau khi bảng `users` đã được đồng bộ ở bước 1.

| Cột cũ (tbluserinsurances) | Cột mới (user_insurances) | Ghi chú |
|---|---|---|
| `userid` | *(tra cứu → `user_id`)* | Không map trực tiếp; dùng `user_id_lookup` để lấy `users.id` |
| `insurancenumber` | `social_insurance_number` | Số sổ BHXH |
| `sidate` | `social_insurance_start_date` | Ngày bắt đầu đóng BHXH |
| `siplace` | `social_insurance_place` | Nơi đăng ký BHXH |
| `healthcareinsurancenumber` | `health_insurance_number` | Số thẻ BHYT |
| `healthplace` | `health_insurance_place` | Nơi đăng ký khám BHYT |
| `end_date` | `end_date` | Ngày kết thúc bảo hiểm |

---

*Cập nhật lần cuối: 2026-03-23 — Nguồn: `config/db_migration.php`*
