<?php

namespace App\Repositories;

use App\Models\Nation;
use App\Models\Office;

/**
 * Class BaseRepository.
 */
class NationRepository extends BaseRepository
{

    protected function model()
    {
        return Nation::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "nations.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('nations.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('nations.name', 'like', "%$name%");
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
