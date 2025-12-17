<?php

namespace App\Services;

use App\Repositories\LeaveCategoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveCategoryService
{
    protected LeaveCategoryRepository $leaveCategoryRepository;

    public function __construct(LeaveCategoryRepository $leaveCategoryRepository)
    {
        $this->leaveCategoryRepository = $leaveCategoryRepository;
    }

    public function search($request){
        return $this->leaveCategoryRepository->search($request);
    }

    public function getAll($request){
        return $this->leaveCategoryRepository->getAll($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id ?? null;
                $response = $this->leaveCategoryRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->leaveCategoryRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LeaveCategoryService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->leaveCategoryRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LeaveCategoryService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
