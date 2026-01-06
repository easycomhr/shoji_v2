<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Support\Carbon;

/**
 * Class BaseRepository.
 */
class TeamRepository extends BaseRepository
{

    protected function model()
    {
        return Team::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $list = $this->model->where('company_id', $company_id)
        ->when(!$request->is_parent, function ($query) {
            return $query->whereNotNull('parent_id');
        })
        ->when($request->is_parent, function ($query) {
            return $query->whereNull('parent_id');
        })
        ->when($s_name = $request->name, function($q) use($s_name) {
            return $q->where('name', 'like', '%'.$s_name.'%');
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
            ->whereNull('parent_id')
            ->get();
    }

}
