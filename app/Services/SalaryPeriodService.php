<?php

namespace App\Services;

use App\Repositories\SalaryPeriodRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryPeriodService
{
    protected SalaryPeriodRepository $salaryPeriodRepository;

    public function __construct(
        SalaryPeriodRepository $salaryPeriodRepository,
    )
    {
        $this->salaryPeriodRepository = $salaryPeriodRepository;
    }

    public function search($request){
        return $this->salaryPeriodRepository->search($request);
    }

    public function getAll($request){
        return $this->salaryPeriodRepository->getAll($request);
    }

    public function store($request){
        $params = [
            'name' => $request->name,
            'standard_working_days' => $request->standard_working_days,
            'from_date' => $request->from_date ? Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d') : null,
            'to_date' => $request->to_date ? Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d') : null,
            'is_clock' => $request->is_clock == 'on' ? 1 : 0,
        ];

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->salaryPeriodRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->salaryPeriodRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("SalaryPeriodService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->salaryPeriodRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("SalaryPeriodService@destroy %s", $e->getMessage()));

            return false;
        }

    }


}
