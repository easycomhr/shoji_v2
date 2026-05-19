<?php

namespace App\Repositories;

use App\Models\SalaryAddition;

class SalaryAdditionRepository extends BaseRepository
{
    protected function model(): string
    {
        return SalaryAddition::class;
    }
}
