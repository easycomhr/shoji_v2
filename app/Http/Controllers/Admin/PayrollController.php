<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\WorkShiftRepository;
use App\Services\AnnualLeaveService;
use App\Services\BaseService;
use App\Services\PayrollService;
use App\Services\WorkShiftService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected PayrollService  $payrollService;

    public function __construct(
        PayrollService  $payrollService,
    )
    {
        $this->payrollService = $payrollService;
    }

    public function index(){
        $title = __("Payroll Service");

        return view('admin.payroll.index', compact('title'));
    }
}
