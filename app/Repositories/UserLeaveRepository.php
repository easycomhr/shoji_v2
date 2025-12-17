<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserLeave;
use Illuminate\Support\Carbon;

/**
 * Class BaseRepository.
 */
class UserLeaveRepository extends BaseRepository
{

    protected function model()
    {
        return UserLeave::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $s_user_code    = $request->s_user_code ?? null;
        $s_from_date    = $request->s_from_date ?? null;
        $s_to_date      = $request->s_to_date ?? null;

        $list = $this->model->where('company_id', $company_id)

            ->when(!empty($s_user_code), function ($query) use ($s_user_code) {
                $query->where('user_code', $s_user_code);
            })
            ->when(!empty($s_from_date), function ($query) use ($s_from_date) {
                $query->whereDate('leave_date', '>=', $s_from_date);
            })
            ->when(!empty($s_to_date), function ($query) use ($s_to_date) {
                $query->whereDate('leave_date', '<=', $s_to_date);
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

    public function getAll($request){
        $company_id = config('constants.COMPANY_ID') ?? null;
        return $this->model
            ->where('company_id', $company_id)
            ->get();
    }

    public function getByParams($params = []){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $minTerminationDate = !empty($params['min_termination_date'])
            ? Carbon::parse($params['min_termination_date'])->toDateString()
            : null;

        return $this->model
            ->where('company_id', $company_id)
            ->where('is_system_user', 0)
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                $query->where('users.id', $params['user_id']);
            })
            ->when($minTerminationDate, function ($query) use ($minTerminationDate) {
                $query->where(function ($subQuery) use ($minTerminationDate) {
                    $subQuery->whereNull('termination_date')
                        ->orWhere('termination_date', '>=', $minTerminationDate);
                });
            })
            ->get();
    }

}
