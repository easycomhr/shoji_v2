<?php

namespace App\Repositories;

use App\Models\Division;

class DivisionRepository extends BaseRepository
{
    protected function model(): string
    {
        return Division::class;
    }
}
