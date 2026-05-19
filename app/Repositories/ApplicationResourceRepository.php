<?php

namespace App\Repositories;

use App\Models\ApplicationResource;

class ApplicationResourceRepository extends BaseRepository
{
    protected function model(): string
    {
        return ApplicationResource::class;
    }
}
