<?php

use App\Http\Controllers\Admin\AllowanceTypeController;
use App\Http\Controllers\Admin\AnnualLeaveController;
use App\Http\Controllers\Admin\AttendancePeriodController;
use App\Http\Controllers\Admin\CalculateSalaryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContractTypeController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ErrorController;
use App\Http\Controllers\Admin\FamilyRelationshipController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\LeaveCategoryController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\LeaveGroupController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\MonthlyLeaveBalanceController;
use App\Http\Controllers\Admin\MonthlyLeaveController;
use App\Http\Controllers\Admin\NationController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\OvertimeTypeController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\QualificationController;
use App\Http\Controllers\Admin\SalaryPeriodController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SyncDataController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TransportationTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserStatusController;
use App\Http\Controllers\Admin\WorkShiftController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test-lang', function () {
    return app()->getLocale();
});

Route::get('/', [AuthController::class, 'showLoginForm'])->name('home_page');

Route::name('home.')->group(function () {
    Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
    Route::get('/product/{sku}', [HomeController::class, 'product'])->name('product');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('show_login_page');
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('show_forgot_page');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function () {

    Route::prefix('admin')->name('admin.')->group(function () {


        Route::prefix('error')->name('error.')->group(function () {
            Route::get('permission-denied', [ErrorController::class, 'permissionDenied'])->name('permission_denied');
        });

        Route::group(['middleware' => ['verify.action']], function () {
            Route::prefix('dashboard')->name('dashboard.')->group(function () {
                Route::get('index', [DashboardController::class, 'index'])->name('index');
            });

            // Sync Data Routes
            Route::prefix('sync_data')->name('sync-data.')->group(function () {

                // Trang chính
                Route::get('/', [SyncDataController::class, 'index'])
                    ->name('index');

                // Import file Excel (sync nhiều tables)
                Route::post('/import', [SyncDataController::class, 'import'])
                    ->name('import');

                // Sync manual (sync từng table) - NEW
                Route::post('/sync-manual', [SyncDataController::class, 'syncManual'])
                    ->name('sync-manual');

                // Tải template Excel
                Route::get('/download-template', [SyncDataController::class, 'downloadTemplate'])
                    ->name('download-template');
            });

//        Route::prefix('user')->name('user.')->group(function () {
//            Route::get('index', [UserController::class, 'index'])->name('index');
//            Route::get('import', [UserController::class, 'import'])->name('import');
//            Route::get('exportExcel', [UserController::class, 'exportExcel'])->name('export_excel');
//            Route::get('add', [UserController::class, 'add'])->name('add');
//            Route::get('edit/{id?}', [UserController::class, 'edit'])->name('edit');
//            Route::post('store/{id?}', [UserController::class, 'store'])->name('store');
//            Route::post('destroy', [UserController::class, 'destroy'])->name('destroy');
//            Route::post('changeLogin', [UserController::class, 'changeLogin'])->name('change_login');
//            Route::post('importExcel', [UserController::class, 'importExcel'])->name('import_excel');
//        });

            Route::prefix('file')->name('file.')->group(function () {
                Route::get('file', [FileController::class, 'index'])->name('index');
                Route::post('upload', [FileController::class, 'upload'])->name('upload');
            });

            Route::prefix('qr_code')->name('qr_code.')->group(function () {
                Route::get('index', [QrCodeController::class, 'index'])->name('index');
                Route::get('show', [QrCodeController::class, 'show'])->name('show');
                Route::get('add', [QrCodeController::class, 'index'])->name('add');
                Route::post('store', [QrCodeController::class, 'store'])->name('store');
            });

            Route::prefix('department')->name('department.')->group(function () {
                Route::get('/index', [DepartmentController::class, 'index'])->name('index');
                Route::get('/search', [DepartmentController::class, 'search'])->name('search');
                Route::post('/store', [DepartmentController::class, 'store'])->name('store');
                Route::post('/delete', [DepartmentController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('user')->name('user.')->group(function () {
                Route::get('/index', [UserController::class, 'index'])->name('index');
                Route::get('/profile/{page}/{code}', [UserController::class, 'profile'])->name('profile');
                Route::get('/import', [UserController::class, 'import'])->name('import');
                Route::post('/import-data', [UserController::class, 'importData'])->name('import_data');
                Route::get('/search', [UserController::class, 'search'])->name('search');
                Route::post('/store-general', [UserController::class, 'storeGeneral'])->name('store_general');
                Route::post('/store-personal', [UserController::class, 'storePersonal'])->name('store_personal');
                Route::post('/store', [UserController::class, 'store'])->name('store');
                Route::post('/delete', [UserController::class, 'destroy'])->name('destroy');

                Route::get('/terminate', [UserController::class, 'terminate'])->name('terminate');
                Route::get('/search-terminate', [UserController::class, 'searchTerminate'])->name('search_terminate');
            });

            Route::prefix('company')->name('company.')->group(function () {
                Route::get('/index', [CompanyController::class, 'index'])->name('index');
                Route::post('/store', [CompanyController::class, 'store'])->name('store');
            });

            Route::prefix('office')->name('office.')->group(function () {
                Route::get('/index', [OfficeController::class, 'index'])->name('index');
                Route::get('/search', [OfficeController::class, 'search'])->name('search');
                Route::post('/store', [OfficeController::class, 'store'])->name('store');
                Route::post('/delete', [OfficeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('parameter')->name('parameter.')->group(function () {
                Route::get('/index', [ParameterController::class, 'index'])->name('index');
                Route::post('/store', [ParameterController::class, 'store'])->name('store');
            });

            Route::prefix('skill')->name('skill.')->group(function () {
                Route::get('/index', [SkillController::class, 'index'])->name('index');
                Route::get('/search', [SkillController::class, 'search'])->name('search');
                Route::post('/store', [SkillController::class, 'store'])->name('store');
                Route::post('/delete', [SkillController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('qualification')->name('qualification.')->group(function () {
                Route::get('/index', [QualificationController::class, 'index'])->name('index');
                Route::get('/search', [QualificationController::class, 'search'])->name('search');
                Route::post('/store', [QualificationController::class, 'store'])->name('store');
                Route::post('/delete', [QualificationController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('grade')->name('grade.')->group(function () {
                Route::get('/index', [GradeController::class, 'index'])->name('index');
                Route::get('/search', [GradeController::class, 'search'])->name('search');
                Route::post('/store', [GradeController::class, 'store'])->name('store');
                Route::post('/delete', [GradeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('user-status')->name('user_status.')->group(function () {
                Route::get('/index', [UserStatusController::class, 'index'])->name('index');
                Route::get('/search', [UserStatusController::class, 'search'])->name('search');
                Route::post('/store', [UserStatusController::class, 'store'])->name('store');
                Route::post('/delete', [UserStatusController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('family-relation')->name('family_relation.')->group(function () {
                Route::get('/index', [FamilyRelationshipController::class, 'index'])->name('index');
                Route::get('/search', [FamilyRelationshipController::class, 'search'])->name('search');
                Route::post('/store', [FamilyRelationshipController::class, 'store'])->name('store');
                Route::post('/delete', [FamilyRelationshipController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('country')->name('country.')->group(function () {
                Route::get('/index', [CountryController::class, 'index'])->name('index');
                Route::get('/search', [CountryController::class, 'search'])->name('search');
                Route::post('/store', [CountryController::class, 'store'])->name('store');
                Route::post('/delete', [CountryController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('education')->name('education.')->group(function () {
                Route::get('/index', [EducationController::class, 'index'])->name('index');
                Route::get('/search', [EducationController::class, 'search'])->name('search');
                Route::post('/store', [EducationController::class, 'store'])->name('store');
                Route::post('/delete', [EducationController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('transportation-type')->name('transportation_type.')->group(function () {
                Route::get('/index', [TransportationTypeController::class, 'index'])->name('index');
                Route::get('/search', [TransportationTypeController::class, 'search'])->name('search');
                Route::post('/store', [TransportationTypeController::class, 'store'])->name('store');
                Route::post('/delete', [TransportationTypeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('leave-category')->name('leave_category.')->group(function () {
                Route::get('/index', [LeaveCategoryController::class, 'index'])->name('index');
                Route::get('/search', [LeaveCategoryController::class, 'search'])->name('search');
                Route::post('/store', [LeaveCategoryController::class, 'store'])->name('store');
                Route::post('/delete', [LeaveCategoryController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('leave-type')->name('leave_type.')->group(function () {
                Route::get('/index', [LeaveTypeController::class, 'index'])->name('index');
                Route::get('/search', [LeaveTypeController::class, 'search'])->name('search');
                Route::post('/store', [LeaveTypeController::class, 'store'])->name('store');
                Route::post('/delete', [LeaveTypeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('work-shift')->name('work_shifts.')->group(function () {
                Route::get('/index', [WorkShiftController::class, 'index'])->name('index');
                Route::get('/search', [WorkShiftController::class, 'search'])->name('search');
                Route::post('/store', [WorkShiftController::class, 'store'])->name('store');
                Route::post('/delete', [WorkShiftController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('master-data')->name('master_data.')->group(function () {
                Route::get('/leave-categories', [MasterDataController::class, 'leaveCategory'])->name('leave_category');
                Route::get('/ot-types', [MasterDataController::class, 'otType'])->name('ot_type');
                Route::get('/users', [MasterDataController::class, 'users'])->name('users');
                Route::get('/years', [MasterDataController::class, 'years'])->name('years');
                Route::get('/months', [MasterDataController::class, 'months'])->name('months');
                Route::get('/leave-types', [MasterDataController::class, 'leaveType'])->name('leave_types');
                Route::get('/leave-sessions', [MasterDataController::class, 'leaveSession'])->name('leave_sessions');
                Route::get('/positions', [MasterDataController::class, 'position'])->name('positions');
                Route::get('/departments', [MasterDataController::class, 'department'])->name('departments');
                Route::get('/get-salary-period', [MasterDataController::class, 'getPeriods'])->name('get_salary_period');
                Route::get('/get-parent-team', [MasterDataController::class, 'parentTeam'])->name('get_parent_team');
            });

            Route::prefix('annual-leave')->name('annual_leave.')->group(function () {
                Route::get('/index', [AnnualLeaveController::class, 'index'])->name('index');
                Route::get('/process', [AnnualLeaveController::class, 'process'])->name('process');
                Route::post('/process-data', [AnnualLeaveController::class, 'processData'])->name('process_data');
                Route::get('/search', [AnnualLeaveController::class, 'search'])->name('search');
                Route::post('/store', [AnnualLeaveController::class, 'store'])->name('store');
                Route::post('/delete', [AnnualLeaveController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('monthly-leave-balance')->name('monthly_leave_balance.')->group(function () {
                Route::get('/index', [MonthlyLeaveBalanceController::class, 'index'])->name('index');
                Route::get('/search', [MonthlyLeaveBalanceController::class, 'search'])->name('search');
                Route::post('/store', [MonthlyLeaveBalanceController::class, 'store'])->name('store');
                Route::post('/delete', [MonthlyLeaveBalanceController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('leave')->name('leave.')->group(function () {
                Route::get('/index', [LeaveController::class, 'index'])->name('index');
                Route::get('/register', [LeaveController::class, 'register'])->name('register');
                Route::get('/import', [LeaveController::class, 'import'])->name('import');
                Route::post('/import-data', [LeaveController::class, 'importData'])->name('import_data');
                Route::get('/search', [LeaveController::class, 'search'])->name('search');
                Route::post('/store', [LeaveController::class, 'store'])->name('store');
                Route::post('/delete', [LeaveController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('payroll')->name('payroll.')->group(function () {
                Route::get('/index', [PayrollController::class, 'index'])->name('index');
                Route::post('/process', [PayrollController::class, 'process'])->name('process');
            });
            Route::prefix('calculate_salary')->name('calculate_salary.')->group(function () {
                Route::get('/index', [CalculateSalaryController::class, 'index'])->name('index');
                Route::post('/process', [CalculateSalaryController::class, 'process'])->name('process');
            });

            Route::prefix('allowance_types')->name('allowance_types.')->group(function () {
                Route::get('/', [AllowanceTypeController::class, 'index'])->name('index');
                Route::get('/search', [AllowanceTypeController::class, 'search'])->name('search');
                Route::post('/store', [AllowanceTypeController::class, 'store'])->name('store');
                Route::post('/destroy', [AllowanceTypeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('nation')->name('nation.')->group(function () {
                Route::get('/', [NationController::class, 'index'])->name('index');
                Route::get('/search', [NationController::class, 'search'])->name('search');
                Route::post('/store', [NationController::class, 'store'])->name('store');
                Route::post('/destroy', [NationController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('contract_types')->name('contract_types.')->group(function () {
                Route::get('/', [ContractTypeController::class, 'index'])->name('index');
                Route::get('/search', [ContractTypeController::class, 'search'])->name('search');
                Route::post('/store', [ContractTypeController::class, 'store'])->name('store');
                Route::post('/destroy', [ContractTypeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('levels')->name('levels.')->group(function () {
                Route::get('/', [LevelController::class, 'index'])->name('index');
                Route::get('/search', [LevelController::class, 'search'])->name('search');
                Route::post('/store', [LevelController::class, 'store'])->name('store');
                Route::post('/destroy', [LevelController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('leave_groups')->name('leave_groups.')->group(function () {
                Route::get('/', [LeaveGroupController::class, 'index'])->name('index');
                Route::get('/search', [LeaveGroupController::class, 'search'])->name('search');
                Route::post('/store', [LeaveGroupController::class, 'store'])->name('store');
                Route::post('/destroy', [LeaveGroupController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('overtime_types')->name('overtime_types.')->group(function () {
                Route::get('/', [OvertimeTypeController::class, 'index'])->name('index');
                Route::get('/search', [OvertimeTypeController::class, 'search'])->name('search');
                Route::post('/store', [OvertimeTypeController::class, 'store'])->name('store');
                Route::post('/destroy', [OvertimeTypeController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('team')->name('team.')->group(function () {
                Route::get('/index', [TeamController::class, 'index'])->name('index');
                Route::get('/subteam', [TeamController::class, 'subteam'])->name('subteam');
                Route::get('/search', [TeamController::class, 'search'])->name('search');
                Route::post('/store', [TeamController::class, 'store'])->name('store');
                Route::post('/delete', [TeamController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('salary_period')->name('salary_period.')->group(function () {
                Route::get('/index', [SalaryPeriodController::class, 'index'])->name('index');
                Route::get('/search', [SalaryPeriodController::class, 'search'])->name('search');
                Route::post('/store', [SalaryPeriodController::class, 'store'])->name('store');
                Route::post('/delete', [SalaryPeriodController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('attendance_period')->name('attendance_period.')->group(function () {
                Route::get('/index', [AttendancePeriodController::class, 'index'])->name('index');
                Route::get('/search', [AttendancePeriodController::class, 'search'])->name('search');
                Route::post('/store', [AttendancePeriodController::class, 'store'])->name('store');
                Route::post('/delete', [AttendancePeriodController::class, 'destroy'])->name('destroy');
            });

        });




    });
});
