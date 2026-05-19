<?php

namespace App\Repositories;

use App\Models\TimeRecorderLog;

class TimeRecorderLogRepository extends BaseRepository
{
    protected function model(): string
    {
        return TimeRecorderLog::class;
    }

    public function getByDateRange(string $from, string $to): mixed
    {
        return $this->model->where('company_id', config('constants.COMPANY_ID'))
            ->whereBetween('record_date', [$from, $to])->get();
    }
}
