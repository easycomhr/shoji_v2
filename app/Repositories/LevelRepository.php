<?php

namespace App\Repositories;

use App\Models\Level;

class LevelRepository extends BaseRepository
{
    protected function model()
    {
        return Level::class;
    }

    public function search($request)
    {
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start  = $request->start ?? 0;
        $length = $request->limit ?? config('constant.default_page_size');
        $code   = $request->code ?? null;
        $name   = $request->name ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select('levels.*')
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('levels.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('levels.name', 'like', "%$name%");
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