<?php

namespace App\Services;

use App\Repositories\ParameterRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParameterService
{
    protected ParameterRepository $parameterRepository;

    public function __construct(
        ParameterRepository $parameterRepository,
    )
    {
        $this->parameterRepository = $parameterRepository;
    }

    public function search($request){
        return $this->parameterRepository->search($request);
    }

    public function getAll($request){
        return $this->parameterRepository->getAll($request);
    }

    public function store($request){
        $params = [
            'key' => $request->key,
            'value' => $request->value,
            'description' => $request->description,
            'apply_date' => $request->apply_date,
        ];

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->parameterRepository->create($params);
            }else{
                if($request->field) {
                    $response = $this->parameterRepository->updateById($request->id, [
                        $request->field => $request->value
                    ]);
                }
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {

            dd($e->getMessage());
            DB::rollBack();
            logger()->error(sprintf("ParameterService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->parameterRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("ParameterService@destroy %s", $e->getMessage()));

            return false;
        }

    }


}
