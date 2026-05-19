<?php

namespace App\Repositories;

use App\Models\UserStatusHistory;

class UserStatusHistoryRepository extends BaseRepository
{
    protected function model(): string
    {
        return UserStatusHistory::class;
    }
}
