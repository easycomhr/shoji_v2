<?php

namespace App\Repositories;

use App\Models\AccumulationLeave;

class AccumulationLeaveRepository extends BaseRepository
{
    protected function model(): string
    {
        return AccumulationLeave::class;
    }
}
