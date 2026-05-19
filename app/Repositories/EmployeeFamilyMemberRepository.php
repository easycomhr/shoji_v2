<?php

namespace App\Repositories;

use App\Models\EmployeeFamilyMember;

class EmployeeFamilyMemberRepository extends BaseRepository
{
    protected function model(): string
    {
        return EmployeeFamilyMember::class;
    }
}
