<?php

namespace App\Repositories;

use App\Models\Reminder;

class ReminderRepository extends BaseRepository
{
    protected function model(): string
    {
        return Reminder::class;
    }
}
