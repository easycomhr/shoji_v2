<?php

namespace App\Services;

use App\Models\AnnualLeave;
use App\Models\MonthlyLeaveBalance;
use App\Models\UserLeave;
use App\Repositories\AnnualLeaveRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkShiftRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnualLeaveService
{
    protected AnnualLeaveRepository $annualLeaveRepository;
    protected UserRepository $userRepository;

    public function __construct(
        AnnualLeaveRepository $annualLeaveRepository,
        UserRepository $userRepository,
    )
    {
        $this->annualLeaveRepository = $annualLeaveRepository;
        $this->userRepository = $userRepository;
    }

    public function search($request){
        return $this->annualLeaveRepository->search($request);
    }

    public function processData($request)
    {
        $year = $request->year ?? null;
        $month = $request->month ?? null;
        $user_id = $request->user_id ?? null;

        DB::beginTransaction();
        try {
            $min_termination_date = Carbon::create($year, $month, 1)->startOfMonth();
            $users = $this->userRepository->getByParams(['user_id' => $user_id, 'min_termination_date' => $min_termination_date]);

            if (!$users) {
                DB::rollBack();
                logger()->error(sprintf("AnnualLeaveService@processData %s", "Users not found"));
                return false;
            }

            foreach ($users as $user) {
                // Truyền $month vào processAnnualLeave
                $annualLeave = $this->processAnnualLeave($user, $year, $month);
                $monthlyLeaveBalance = $this->processMonthlyLeaveBalance($user, $year, $month);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AnnualLeaveService@processData %s", $e->getMessage()));
            return false;
        }
    }

    protected function processAnnualLeave($user, $year, $month = null)
    {
        $company_id = $user->company_id;
        $annualLeave = AnnualLeave::where('user_id', $user->id)->where('year', $year)->first();

        if (!$annualLeave) {
            $annualLeave = new AnnualLeave();
            $annualLeave->company_id = $company_id;
            $annualLeave->user_id = $user->id;
            $annualLeave->user_code = $user->code ?? null;
            $annualLeave->year = $year;
        }

        $previousYear = $year - 1;
        $previousAnnualLeave = AnnualLeave::where('user_id', $user->id)
            ->where('year', $previousYear)
            ->first();
        $annual_transfer = $previousAnnualLeave ? $previousAnnualLeave->remaining_leave_days : 0;

        // Truyền $month nếu có, nếu không thì tính cả năm
        $total_accrued_in_year = $this->calculateTotalAccruedInYear($user, $year, $month ?? 12);

        $used_leave_days = $this->calculateUsedLeaveDays($user, $year);
        $transfer_used_by_march = $this->calculateTransferUsedByMarch($user, $year);

        $annualLeave->annual_transfer = $annual_transfer;
        $annualLeave->total_accrued_in_year = $total_accrued_in_year;
        $annualLeave->total_leave_days = $annual_transfer + $total_accrued_in_year;
        $annualLeave->used_leave_days = $used_leave_days;
        $annualLeave->remaining_leave_days = $annualLeave->total_leave_days - $used_leave_days;
        $annualLeave->transfer_used_by_march = $transfer_used_by_march;
        $annualLeave->transfer_cleared = 0;

        $annualLeave->save();

        return $annualLeave;
    }

    protected function processMonthlyLeaveBalance($user, $year, $month)
    {
        $monthlyLeaveBalance = MonthlyLeaveBalance::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$monthlyLeaveBalance) {
            $monthlyLeaveBalance = new MonthlyLeaveBalance();
            $monthlyLeaveBalance->company_id = $user->company_id;
            $monthlyLeaveBalance->user_id = $user->id;
            $monthlyLeaveBalance->user_code = $user->code ?? null;
            $monthlyLeaveBalance->year = $year;
            $monthlyLeaveBalance->month = $month;
        }

        // Calculate opening_balance
        $opening_balance = $this->calculateOpeningBalance($user, $year, $month);

        // Calculate monthly_accrued
        $monthly_accrued = $this->calculateMonthlyAccrued($user, $year, $month);

        // Calculate monthly_used
        $monthly_used = $this->calculateMonthlyUsed($user, $year, $month);

        // Calculate monthly_remaining
        $monthly_remaining = $opening_balance + $monthly_accrued - $monthly_used;

        // Update MonthlyLeaveBalance fields
        $monthlyLeaveBalance->opening_balance = $opening_balance;
        $monthlyLeaveBalance->monthly_accrued = $monthly_accrued;
        $monthlyLeaveBalance->monthly_used = $monthly_used;
        $monthlyLeaveBalance->monthly_remaining = $monthly_remaining;

        $monthlyLeaveBalance->save();

        return $monthlyLeaveBalance;
    }

    protected function calculateOpeningBalance($user, $year, $month)
    {
        if ($month == 1) {
            $prevYear = $year - 1;
            $prevMonthBalance = MonthlyLeaveBalance::where('user_id', $user->id)
                ->where('company_id', $user->company_id)
                ->where('year', $prevYear)
                ->where('month', 12)
                ->first();
            return $prevMonthBalance ? $prevMonthBalance->monthly_remaining : 0;
        }

        $prevMonth = $month - 1;
        $prevMonthBalance = MonthlyLeaveBalance::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->where('year', $year)
            ->where('month', $prevMonth)
            ->first();
        return $prevMonthBalance ? $prevMonthBalance->monthly_remaining : 0;
    }

    protected function calculateMonthlyAccrued($user, $year, $month)
    {
        // Assume hire_date is available in the User model
        $hireDate = Carbon::parse($user->probation_start);
        $currentDate = Carbon::create($year, $month, 1);

        if ($hireDate->year == $year && $hireDate->month == $month && $hireDate->day <= 15) {
            return 1;
        } elseif ($hireDate->isBefore($currentDate)) {
            return 1;
        }

        return 0;
    }

    protected function calculateMonthlyUsed($user, $year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $totalHours = UserLeave::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->whereBetween('leave_date', [$startOfMonth, $endOfMonth])
            ->whereIn('leave_type_id', [1, 5])
            ->sum('leave_amount');

        return $totalHours / 8;
    }

    protected function calculateTotalAccruedInYear($user, $year, $endMonth = 12)
    {
        $hireDate = Carbon::parse($user->probation_start);
        $months = 0;

        for ($month = 1; $month <= $endMonth; $month++) {
            if ($hireDate->year == $year && $month == $hireDate->month && $hireDate->day > 15) {
                continue;
            }
            if ($hireDate->isBefore(Carbon::create($year, $month, 1))) {
                $months++;
            }
        }

        return $months;
    }

    protected function calculateUsedLeaveDays($user, $year)
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear();
        $endOfYear = $startOfYear->copy()->endOfYear();

        $totalHours = UserLeave::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->whereBetween('leave_date', [$startOfYear, $endOfYear])
            ->whereIn('leave_type_id', [1, 5])
            ->sum('leave_amount');

        return $totalHours / 8;
    }

    protected function calculateTransferUsedByMarch($user, $year)
    {
        $startOfYear = Carbon::create($year, 1, 1)->startOfYear();
        $endOfMarch = Carbon::create($year, 3, 31)->endOfDay();

        $totalHours = UserLeave::where('user_id', $user->id)
            ->where('company_id', $user->company_id)
            ->whereBetween('leave_date', [$startOfYear, $endOfMarch])
            ->whereIn('leave_type_id', [1, 5])
            ->sum('leave_amount');

        return $totalHours / 8;
    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->annualLeaveRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AnnualLeaveService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
