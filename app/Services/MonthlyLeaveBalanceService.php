<?php

namespace App\Services;

use App\Models\AnnualLeave;
use App\Models\MonthlyLeaveBalance;
use App\Models\UserLeave;
use App\Repositories\AnnualLeaveRepository;
use App\Repositories\MonthlyLeaveBalanceRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkShiftRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonthlyLeaveBalanceService
{
    protected MonthlyLeaveBalanceRepository $monthlyLeaveBalanceRepository;
    protected UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
        MonthlyLeaveBalanceRepository $monthlyLeaveBalanceRepository,
    )
    {
        $this->userRepository = $userRepository;
        $this->monthlyLeaveBalanceRepository = $monthlyLeaveBalanceRepository;
    }

    public function search($request){
        return $this->monthlyLeaveBalanceRepository->search($request);
    }


}
