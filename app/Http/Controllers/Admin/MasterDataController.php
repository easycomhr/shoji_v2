<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryPeriod;
use App\Services\DepartmentService;
use App\Services\LeaveCategoryService;
use App\Services\LeaveTypeService;
use App\Services\PositionService;
use App\Services\TeamService;
use App\Services\UserService;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    protected LeaveTypeService $leaveTypeService;
    protected LeaveCategoryService  $leaveCategoryService;
    protected UserService $userService;
    protected PositionService  $positionService;
    protected DepartmentService $departmentService;
    protected TeamService $teamService;

    public function __construct(
        LeaveTypeService $leaveTypeService,
        LeaveCategoryService $leaveCategoryService,
        UserService $userService,
        PositionService  $positionService,
        DepartmentService $departmentService,
        TeamService $teamService,
    )
    {
        $this->leaveTypeService = $leaveTypeService;
        $this->leaveCategoryService = $leaveCategoryService;
        $this->userService = $userService;
        $this->positionService = $positionService;
        $this->departmentService = $departmentService;
        $this->teamService = $teamService;
    }

    public function index(){
        $title = "Leave Categories";

        return view('admin.leave_type.index', compact('title'));
    }

    public function leaveCategory(Request $request)
    {
        $response = $this->leaveCategoryService->getAll($request);

        return json_encode([
            "success"   => true,
            "data"      => $response->toArray() ?? [],
            "results"   => count($response) ?? 0,
        ]);
    }

    public function leaveType(Request $request)
    {
        $response = $this->leaveTypeService->getAll($request);

        return json_encode([
            "success"   => true,
            "data"      => $response->toArray() ?? [],
            "results"   => count($response) ?? 0,
        ]);
    }

    public function otType(Request $request)
    {
        $response = config('constants.ot_types');
        return  response()->json([
            "success"   => true,
            "data"      => array_values($response) ?? [],
            "results"   => count($response) ?? 0,
        ]);
    }

    public function leaveSession(Request $request)
    {
        $response = config('constants.leave_sessions');
        return  response()->json([
            "success"   => true,
            "data"      => array_values($response) ?? [],
            "results"   => count($response) ?? 0,
        ]);
    }

    public function users(Request $request)
    {
        $response = $this->userService->getAll($request);

        $users = [];
        foreach ($response as $user) {
            $users[] = [
                'id' => $user->id,
                'code' => $user->code,
                'name' => $user->name,
                'custom_name' => $user->code . ' - ' . $user->name,
            ];
        }

        return json_encode([
            "success"   => true,
            "data"      => $users,
            "results"   => count($response) ?? 0,
        ]);
    }

    public function years(Request $request)
    {
        $years = [];
        $current_year = date('Y');
        for($y = $current_year; $y >= $current_year - 10; $y--){
            $years[] = ['id' => $y, 'name' => $y];
        }

        return json_encode([
            "success"   => true,
            "data"      => $years,
            "results"   => count($years) ?? 0,
        ]);
    }

    public function months(Request $request)
    {
        $months = [];

        for($m = 1; $m <= 12; $m++){
            $months[] = ['id' => str_pad($m, 2, '0', STR_PAD_LEFT), 'name' => str_pad($m, 2, '0', STR_PAD_LEFT)];
        }

        return json_encode([
            "success"   => true,
            "data"      => $months,
            "results"   => count($months) ?? 0,
        ]);
    }

    public function position(Request $request)
    {
        $response = $this->positionService->getAll($request);

        $list = [];
        foreach ($response as $item) {
            $list[] = [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
            ];
        }

        return json_encode([
            "success"   => true,
            "data"      => $list,
            "results"   => count($response) ?? 0,
        ]);
    }

    public function department(Request $request)
    {
        $response = $this->departmentService->getAll($request);

        $list = [];
        foreach ($response as $item) {
            $list[] = [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
            ];
        }

        return json_encode([
            "success"   => true,
            "data"      => $list,
            "results"   => count($response) ?? 0,
        ]);
    }


    public function getPeriods(Request $request)
    {
        $data = SalaryPeriod::query()
            ->select('id', 'from_date', 'to_date', 'name', 'is_locked', 'standard_working_days')
            ->where('company_id', config('constants.COMPANY_ID'))
            ->orderByDesc('to_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data->toArray() ?? [],
            'results' => $data->count(),
        ]);
    }

    public function parentTeam(Request $request)
    {
        $response = $this->teamService->getAll($request);

        return json_encode([
            "success"   => true,
            "data"      => $response->toArray() ?? [],
            "results"   => count($response) ?? 0,
        ]);
    }

}
