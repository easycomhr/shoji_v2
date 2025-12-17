<?php

namespace App\Services;

use App\Repositories\OfficeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficeService
{
    protected OfficeRepository $officeRepository;

    public function __construct(OfficeRepository $officeRepository)
    {
        $this->officeRepository = $officeRepository;
    }

    public function search($request){
        return $this->officeRepository->search($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->officeRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->officeRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("OfficeService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            $this->officeRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("OfficeService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
