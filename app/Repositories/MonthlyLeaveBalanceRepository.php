<?php

namespace App\Repositories;

use App\Models\AnnualLeave;
use App\Models\MonthlyLeaveBalance;
use App\Models\WorkShift;

/**
 * Class BaseRepository.
 */
class MonthlyLeaveBalanceRepository extends BaseRepository
{

    protected function model()
    {
        return MonthlyLeaveBalance::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $s_user_code    = $request->s_user_code ?? null;
        $s_year         = $request->s_year ?? null;
        $s_month        = $request->s_month ?? null;

        if(empty($s_year) && empty($s_user_code)){
            return null;
        }

        $list = $this->model->where('company_id', $company_id)
            ->when(!empty($s_user_code), function ($query) use ($s_user_code) {
                $query->where('user_code', $s_user_code);
            })
            ->when(!empty($s_year), function ($query) use ($s_year) {
                $query->where('year', $s_year);
            })
            ->when(!empty($s_month), function ($query) use ($s_month) {
                $query->where('month', $s_month);
            })

        ;

        $recordsTotal = $list->count();

        if ($length) {
            $list = $list->skip($start)->take($length);
        }

        $list = $list->get();
        $results = $list ? $list->toArray() : [];

        return [
            'results' =>$results,
            'recordsTotal' =>$recordsTotal,
        ];

    }


}
