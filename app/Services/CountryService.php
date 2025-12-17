<?php

namespace App\Services;

use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CountryService
{
    protected CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function search($request){
        return $this->countryRepository->search($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->countryRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->countryRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("CountryService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        $params = $request->all();

        DB::beginTransaction();
        try {
            $this->countryRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("CountryService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
