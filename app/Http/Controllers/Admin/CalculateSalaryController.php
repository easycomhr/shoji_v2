<?php

// =============================================================================
// CONTROLLER - Xử lý request tính lương
// =============================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CalculateSalaryService;
use App\Models\SalaryPeriod;
use App\Models\Department;
use App\Models\User;
use App\Models\UserMonthlySalaryDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * CalculateSalaryController - Controller xử lý tính lương
 *
 * Chức năng chính:
 * - Lấy danh sách kỳ lương và phòng ban
 * - Tính lương cho nhân viên theo nhiều chế độ khác nhau
 * - Quản lý việc khóa/mở khóa kỳ lương
 */
class CalculateSalaryController extends Controller
{
    protected $salaryCalculationService;

    public function __construct(CalculateSalaryService $salaryCalculationService)
    {
        $this->salaryCalculationService = $salaryCalculationService;
    }

    public function index(){
        $title = __("Calculate salary");

        return view('admin.calculate_salary.index', compact('title'));
    }

    /**
     * Tính lương cho nhân viên
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function process(Request $request): JsonResponse
    {
        // Validate request
        $validated = $request->validate([
            'salaryPeriod' => 'required|exists:salary_periods,id',
            'calculateBy' => 'required|in:EmployeeID,Department,All',
            'employee_id' => 'required_if:calculateBy,EmployeeID|exists:users,id',
            'department_id' => 'required_if:calculateBy,Department|exists:departments,id'
        ]);

        try {
            // Kiểm tra kỳ lương có bị khóa không
            $salaryPeriod = SalaryPeriod::find($validated['salaryPeriod']);
            if ($salaryPeriod->is_locked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kỳ lương đã được khóa, không thể tính lại!'
                ], 400);
            }
            // Thực hiện tính lương theo chế độ
            $totalCalculated = $this->salaryCalculationService->calculateSalary([
                'salary_period_id' => $validated['salaryPeriod'],
                'calculate_for' => $validated['calculateBy'],
                'employee_id' => $validated['employee_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'total' => $totalCalculated,
                'message' => "Đã tính lương thành công cho {$totalCalculated} nhân viên"
            ]);

        } catch (\Exception $e) {
            \Log::error('Lỗi tính lương: ' . $e->getMessage(), [
                'request' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tính lương: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Khóa kỳ lương
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lockSalaryPeriod(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salary_period_id' => 'required|exists:salary_periods,id'
        ]);

        try {
            $salaryPeriod = SalaryPeriod::find($validated['salary_period_id']);
            $salaryPeriod->lock(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Đã khóa kỳ lương thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi khóa kỳ lương: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mở khóa kỳ lương
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unlockSalaryPeriod(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salary_period_id' => 'required|exists:salary_periods,id'
        ]);

        try {
            $salaryPeriod = SalaryPeriod::find($validated['salary_period_id']);
            $salaryPeriod->unlock();

            return response()->json([
                'success' => true,
                'message' => 'Đã mở khóa kỳ lương thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi mở khóa kỳ lương: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy kết quả tính lương
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSalaryResults(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salary_period_id' => 'required|exists:salary_periods,id',
            'department_id' => 'nullable|exists:departments,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        try {
            $salaryPeriod = SalaryPeriod::find($validated['salary_period_id']);

            $query = UserMonthlySalaryDetail::with('user')
                ->forPeriod($salaryPeriod->to_date->month, $salaryPeriod->to_date->year);

            if (isset($validated['department_id'])) {
                $department = Department::find($validated['department_id']);
                $query->byDepartment($department->name);
            }

            $results = $query->paginate($validated['per_page'] ?? 50);

            return response()->json([
                'success' => true,
                'data' => $results->items(),
                'pagination' => [
                    'current_page' => $results->currentPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                    'last_page' => $results->lastPage()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy kết quả tính lương: ' . $e->getMessage()
            ], 500);
        }
    }
}