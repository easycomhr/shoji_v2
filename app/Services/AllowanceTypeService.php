<?php

namespace App\Services;

use App\Repositories\AllowanceTypeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AllowanceTypeService extends BaseService
{
    protected AllowanceTypeRepository $allowanceTypeRepository;

    public function __construct(AllowanceTypeRepository $allowanceTypeRepository)
    {
        $this->allowanceTypeRepository = $allowanceTypeRepository;
    }

    public function search($request)
    {
        return $this->allowanceTypeRepository->search($request);
    }

    public function store($request)
    {
        $params = $request->all();
        DB::beginTransaction();
        try {
            if (empty($request->id)) {
                $params['company_id'] = Auth::user()->company_id ?? null;
                $response = $this->allowanceTypeRepository->create($params);
            } else {
                if ($request->field) {
                    $params[$request->field] = $request->value;
                }
                $response = $this->allowanceTypeRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AllowanceTypeService@store %s", $e->getMessage()));
            return false;
        }
    }

    public function destroy($request)
    {
        DB::beginTransaction();
        try {
            $response = $this->allowanceTypeRepository->deleteById($request->id);
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("AllowanceTypeService@destroy %s", $e->getMessage()));
            return false;
        }
    }
}