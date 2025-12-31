<?php

namespace App\Services;

use App\Repositories\LevelRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LevelService
{
    protected LevelRepository $levelRepository;

    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    public function search($request)
    {
        return $this->levelRepository->search($request);
    }

    public function store($request)
    {
        $params = $request->all();

        DB::beginTransaction();
        try {
            if (empty($request->id)) {
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->levelRepository->create($params);
            } else {
                if ($request->field) {
                    $params[$request->field] = $request->value;
                }
                $response = $this->levelRepository->updateById($request->id, $params);
            }

            DB::commit();
            return $response;

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LevelService@store %s", $e->getMessage()));
            return false;
        }
    }

    public function destroy($request)
    {
        DB::beginTransaction();
        try {
            $response = $this->levelRepository->deleteById($request->id);
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("LevelService@destroy %s", $e->getMessage()));
            return false;
        }
    }
}