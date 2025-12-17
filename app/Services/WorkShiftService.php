<?php

namespace App\Services;

use App\Repositories\WorkShiftRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkShiftService
{
    protected WorkShiftRepository $workShiftRepository;

    public function __construct(WorkShiftRepository $workShiftRepository)
    {
        $this->workShiftRepository = $workShiftRepository;
    }

    public function search($request){
        return $this->workShiftRepository->search($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {

            if(isset($params['is_day_off'])){
                $params['is_day_off'] = $params['is_day_off'] == 'true' ? 1 : 0;
            }

            if(isset($params['is_night_shift'])){
                $params['is_night_shift'] = $params['is_night_shift'] == 'true' ? 1 : 0;
            }

            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id ?? null;
                $response = $this->workShiftRepository->create($params);
            }else{

                if($request->field){
                    $value = $request->value;
                    $field = $request->field;
                    if(in_array($value, ['true','false'])){
                        $value = $value == 'true' ? 1 : 0;
                    }

                    if(in_array($field, ['work_start', 'work_end'])){
                        $value = Carbon::parse($value)->format('H:i:s');
                    }

                    $params[$field] = $value;
                }

                $response = $this->workShiftRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("WorkShiftService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->workShiftRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("WorkShiftService@destroy %s", $e->getMessage()));

            return false;
        }

    }

}
