<?php

namespace App\Services;

use App\Models\ShiftKey;
use Carbon\Carbon;

class HrService
{
    public function getShiftKey(int $userId, Carbon $date): ?int
    {
        $col = 'd' . str_pad($date->day, 2, '0', STR_PAD_LEFT);

        return ShiftKey::where('user_id', $userId)
            ->where('year', $date->year)
            ->where('month', $date->month)
            ->value($col);
    }
}
