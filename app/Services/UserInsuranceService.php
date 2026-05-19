<?php

namespace App\Services;

use App\Repositories\UserInsuranceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserInsuranceService extends BaseService
{
    protected UserInsuranceRepository $userInsuranceRepository;

    public function __construct(UserInsuranceRepository $userInsuranceRepository)
    {
        $this->userInsuranceRepository = $userInsuranceRepository;
    }

    public function search($request)
    {
        return $this->userInsuranceRepository->search($request);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if (empty($request->id)) {
                $response = $this->userInsuranceRepository->create([
                    'user_id'                      => $request->user_id,
                    'social_insurance_number'      => $request->social_insurance_number,
                    'social_insurance_start_date'  => $request->social_insurance_start_date,
                    'social_insurance_place'       => $request->social_insurance_place,
                    'health_insurance_number'      => $request->health_insurance_number,
                    'health_insurance_place'       => $request->health_insurance_place,
                    'end_date'                     => $request->end_date,
                    'is_locked'                    => $request->is_locked ?? false,
                ]);
            } else {
                $data = [];
                if ($request->field) {
                    $data[$request->field] = $request->value;
                } else {
                    $data = $request->only([
                        'user_id',
                        'social_insurance_number',
                        'social_insurance_start_date',
                        'social_insurance_place',
                        'health_insurance_number',
                        'health_insurance_place',
                        'end_date',
                        'is_locked',
                    ]);
                }
                $response = $this->userInsuranceRepository->updateById($request->id, $data);
            }

            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserInsuranceService@store %s", $e->getMessage()));
            return false;
        }
    }

    public function destroy($request)
    {
        DB::beginTransaction();
        try {
            $this->userInsuranceRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("UserInsuranceService@destroy %s", $e->getMessage()));
            return false;
        }
    }
}
