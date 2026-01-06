<?php

namespace App\Services;

use App\Repositories\AttendancePeriodRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendancePeriodService
{
    protected AttendancePeriodRepository $attendancePeriodRepository;

    public function __construct(
        AttendancePeriodRepository $attendancePeriodRepository,
    )
    {
        $this->attendancePeriodRepository = $attendancePeriodRepository;
    }

    public function search($request){
        return $this->attendancePeriodRepository->search($request);
    }

    public function getAll($request){
        return $this->attendancePeriodRepository->getAll($request);
    }

    public function store($request){
        $params = [
            'name' => $request->name,
            'standard_working_days' => $request->standard_working_days,
            'from_date' => $request->from_date ? Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d') : null,
            'to_date' => $request->to_date ? Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d') : null,
            'is_clocked' => $request->is_clocked == 'on' ? 1 : 0,
        ];

        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->attendancePeriodRepository->create($params);
            }else{
                if($request->field) {
                    $response = $this->attendancePeriodRepository->updateById($request->id, [
                        $request->field => $request->value
                    ]);
                }
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AttendancePeriodService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->attendancePeriodRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AttendancePeriodService@destroy %s", $e->getMessage()));

            return false;
        }

    }


}
