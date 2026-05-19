<?php

namespace App\Repositories;

use App\Models\ShiftSchedule;

class ShiftScheduleRepository extends BaseRepository
{
    protected function model(): string
    {
        return ShiftSchedule::class;
    }

    public function getByUserAndDate(int $userId, string $date): mixed
    {
        return $this->model->where('company_id', config('constants.COMPANY_ID'))
            ->where('user_id', $userId)->where('schedule_date', $date)->get();
    }
}
