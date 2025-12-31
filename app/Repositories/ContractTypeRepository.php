<?php

namespace App\Repositories;

use App\Models\ContractType;

/**
 * Class ContractTypeRepository.
 */
class ContractTypeRepository extends BaseRepository
{

    protected function model()
    {
        return ContractType::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "contract_types.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('contract_types.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('contract_types.name', 'like', "%$name%");
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