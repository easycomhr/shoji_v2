<?php

namespace App\Services;

use App\Repositories\TransportationTypeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransportationTypeService
{
    protected TransportationTypeRepository $transportationTypeRepository;

    public function __construct(TransportationTypeRepository $transportationTypeRepository)
    {
        $this->transportationTypeRepository = $transportationTypeRepository;
    }

    public function search($request){
        return $this->transportationTypeRepository->search($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id ?? null;
                $response = $this->transportationTypeRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->transportationTypeRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("TransportationTypeService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->transportationTypeRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("TransportationTypeService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
