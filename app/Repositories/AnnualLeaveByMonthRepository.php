<?php

namespace App\Repositories;

use App\Models\AnnualLeaveByMonth;

class AnnualLeaveByMonthRepository extends BaseRepository
{
    protected function model(): string
    {
        return AnnualLeaveByMonth::class;
    }
}
