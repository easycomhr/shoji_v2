<?php

namespace App\Repositories;

use App\Models\EmployeeAllowance;

class EmployeeAllowanceRepository extends BaseRepository
{
    protected function model(): string
    {
        return EmployeeAllowance::class;
    }
}
