<?php

namespace App\Repositories;

use App\Models\SystemParameter;

/**
 * Class BaseRepository.
 */
class ParameterRepository extends BaseRepository
{

    protected function model()
    {
        return SystemParameter::class;
    }

    public function search($request)
    {
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $list = $this->model->where('company_id', $company_id)
        ->when($s_key = $request->key, function($q) use($s_key) {
            return $q->where('key', 'like', '%'.$s_key.'%');
        });
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
