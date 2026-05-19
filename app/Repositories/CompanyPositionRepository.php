<?php

namespace App\Repositories;

use App\Models\CompanyPosition;

class CompanyPositionRepository extends BaseRepository
{
    protected function model(): string
    {
        return CompanyPosition::class;
    }
}
