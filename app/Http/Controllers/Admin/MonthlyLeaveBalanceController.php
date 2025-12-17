<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WorkShiftRepository;
use App\Services\AnnualLeaveService;
use App\Services\BaseService;
use App\Services\MonthlyLeaveBalanceService;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;

class MonthlyLeaveBalanceController extends Controller
{
    protected AnnualLeaveService  $annualLeaveService;
    protected MonthlyLeaveBalanceService $monthlyLeaveBalanceService;

    public function __construct(
        AnnualLeaveService $annualLeaveService,
        MonthlyLeaveBalanceService $monthlyLeaveBalanceService,
    )
    {
        $this->annualLeaveService  = $annualLeaveService;
        $this->monthlyLeaveBalanceService = $monthlyLeaveBalanceService;
    }

    public function index(){
        $title = __("Monthly Leave Balance Management");

        return view('admin.monthly_leave_balance.index', compact('title'));
    }

    public function search(Request $request)
    {

        $response = $this->monthlyLeaveBalanceService->search($request);

        return json_encode([
            "success"   => true,
            "rows"      => $response['results'] ?? [],
            "total"     => $response['recordsTotal'] ?? 0,
        ]);
    }


}
