<?php

namespace App\Repositories;

use App\Models\FamilyRelationship;

/**
 * Class BaseRepository.
 */
class FamilyRelationshipRepository extends BaseRepository
{

    protected function model()
    {
        return FamilyRelationship::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "family_relationships.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('family_relationships.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('family_relationships.name', 'like', "%$name%");
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
