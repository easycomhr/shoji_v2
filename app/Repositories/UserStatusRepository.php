<?php

namespace App\Repositories;

use App\Models\UserStatus;

/**
 * Class BaseRepository.
 */
class UserStatusRepository extends BaseRepository
{

    protected function model()
    {
        return UserStatus::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "user_statuses.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('user_statuses.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('user_statuses.name', 'like', "%$name%");
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
            ->where('company_id', config('constants.COMPANY_ID'))
            ->get();
    }



}
