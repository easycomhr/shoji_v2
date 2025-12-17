<?php

namespace App\Repositories;

use App\Models\AnnualLeave;
use App\Models\WorkShift;

/**
 * Class BaseRepository.
 */
class AnnualLeaveRepository extends BaseRepository
{

    protected function model()
    {
        return AnnualLeave::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $s_user_code    = $request->s_user_code ?? null;
        $s_year         = $request->s_year ?? null;

        if(empty($s_year) && empty($s_user_code)){
            return null;
        }

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "annual_leaves.*"
            )
            ->when(!empty($s_user_code), function ($query) use ($s_user_code) {
                $query->where('annual_leaves.user_code', $s_user_code);
            })
            ->when(!empty($s_year), function ($query) use ($s_year) {
                $query->where('annual_leaves.year', $s_year);
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
