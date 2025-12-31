<?php

namespace App\Services;

use App\Repositories\LeaveGroupRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveGroupService extends BaseService
{
    protected LeaveGroupRepository $leaveGroupRepository;

    public function __construct(LeaveGroupRepository $leaveGroupRepository)
    {
        $this->leaveGroupRepository = $leaveGroupRepository;
    }

    public function search($request)
    {
        return $this->leaveGroupRepository->search($request);
    }

    public function store($request)
    {
        $params = $request->all();
        DB::beginTransaction();
        try {
            if (empty($request->id)) {
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->leaveGroupRepository->create($params);
            } else {
                if ($request->field) {
                    $params[$request->field] = $request->value;
                }
                $response = $this->leaveGroupRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LeaveGroupService@store %s", $e->getMessage()));
            return false;
        }
    }

    public function destroy($request)
    {
        DB::beginTransaction();
        try {
            $this->leaveGroupRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LeaveGroupService@destroy %s", $e->getMessage()));
            return false;
        }
    }
}