<?php

namespace App\Repositories;

use App\Models\UserPermission;

class UserPermissionRepository extends BaseRepository
{
    protected function model(): string
    {
        return UserPermission::class;
    }
}
