<?php

namespace App\Repositories;

use App\Models\LeaveGroup;

class LeaveGroupRepository extends BaseRepository
{
    protected function model()
    {
        return LeaveGroup::class;
    }

    public function search($request)
    {
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start  = $request->start ?? 0;
        $length = $request->limit ?? config('constant.default_page_size');
        $code   = $request->code ?? null;
        $name   = $request->name ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "leave_groups.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('leave_groups.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('leave_groups.name', 'like', "%$name%");
            });

        $recordsTotal = $list->count();

        if ($length) {
            $list = $list->skip($start)->take($length);
        }

        $list = $list->get();
        $results = $list ? $list->toArray() : [];

        return [
            'results'      => $results,
            'recordsTotal' => $recordsTotal,
        ];
    }
}