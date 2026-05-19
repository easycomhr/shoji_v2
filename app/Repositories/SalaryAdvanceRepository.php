<?php

namespace App\Repositories;

use App\Models\SalaryAdvance;

class SalaryAdvanceRepository extends BaseRepository
{
    protected function model(): string
    {
        return SalaryAdvance::class;
    }
}
