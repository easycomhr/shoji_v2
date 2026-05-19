<?php

namespace App\Repositories;

use App\Models\SalaryDeduction;

class SalaryDeductionRepository extends BaseRepository
{
    protected function model(): string
    {
        return SalaryDeduction::class;
    }
}
