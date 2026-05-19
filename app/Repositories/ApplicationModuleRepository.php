<?php

namespace App\Repositories;

use App\Models\ApplicationModule;

class ApplicationModuleRepository extends BaseRepository
{
    protected function model(): string
    {
        return ApplicationModule::class;
    }
}
