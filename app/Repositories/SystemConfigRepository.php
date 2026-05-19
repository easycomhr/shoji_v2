<?php

namespace App\Repositories;

use App\Models\SystemConfig;

class SystemConfigRepository extends BaseRepository
{
    protected function model(): string
    {
        return SystemConfig::class;
    }
}
