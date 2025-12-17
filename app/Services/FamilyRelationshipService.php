<?php

namespace App\Services;

use App\Repositories\FamilyRelationshipRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FamilyRelationshipService
{
    protected FamilyRelationshipRepository $familyRelationshipRepository;

    public function __construct(FamilyRelationshipRepository $familyRelationshipRepository)
    {
        $this->familyRelationshipRepository = $familyRelationshipRepository;
    }

    public function search($request){
        return $this->familyRelationshipRepository->search($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->familyRelationshipRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->familyRelationshipRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("FamilyRelationshipService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->familyRelationshipRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("FamilyRelationshipService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
