<?php

namespace App\Services;

use App\Repositories\NationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NationService
{
    protected NationRepository $nationRepository;

    public function __construct(NationRepository $nationRepository)
    {
        $this->nationRepository = $nationRepository;
    }

    public function search($request){
        return $this->nationRepository->search($request);
    }

    public function getAll($request){
        return $this->nationRepository->getAll($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->nationRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->nationRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("NationService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            $this->nationRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("NationService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
