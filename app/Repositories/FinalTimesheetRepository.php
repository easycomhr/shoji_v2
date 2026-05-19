<?php

namespace App\Repositories;

use App\Models\FinalTimesheet;

class FinalTimesheetRepository extends BaseRepository
{
    protected function model(): string
    {
        return FinalTimesheet::class;
    }

    public function getBySalaryPeriod(int $salaryPeriodId): mixed
    {
        return $this->model->where('company_id', config('constants.COMPANY_ID'))
            ->where('salary_period_id', $salaryPeriodId)->get();
    }
}
