<?php

namespace App\Services;

use App\Repositories\PositionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PositionService
{
    protected PositionRepository $positionRepository;

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;
    }

    public function search($request){
        return $this->positionRepository->search($request);
    }

    public function getAll($request){
        return $this->positionRepository->getAll($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->positionRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->positionRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("PositionService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            $this->positionRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("PositionService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
