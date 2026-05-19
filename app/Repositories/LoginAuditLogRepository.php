<?php

namespace App\Repositories;

use App\Models\LoginAuditLog;

class LoginAuditLogRepository extends BaseRepository
{
    protected function model(): string
    {
        return LoginAuditLog::class;
    }
}
