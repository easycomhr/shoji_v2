<?php

namespace App\Repositories;

use App\Models\Grade;
use App\Models\LeaveType;

/**
 * Class BaseRepository.
 */
class LeaveTypeRepository extends BaseRepository
{

    protected function model()
    {
        return LeaveType::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;
        $orderBy        = $request->orderBy ?? 'id'; // Trường sắp xếp mặc định là 'id'
        $sortDir        = $request->sortDir ?? 'DESC'; // Hướng sắp xếp mặc định là 'DESC'

        $list = $this->model
            ->with('leave_category')
            ->where('company_id', $company_id)
            ->select(
                "leave_types.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('leave_types.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('leave_types.name', 'like', "%$name%");
            })
            ->orderBy($orderBy, $sortDir)
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


}
