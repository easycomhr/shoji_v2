<?php

namespace App\Services;

use App\Repositories\TeamRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamService
{
    protected TeamRepository $teamRepository;

    public function __construct(
        TeamRepository $teamRepository,
    )
    {
        $this->teamRepository = $teamRepository;
    }

    public function search($request){
        return $this->teamRepository->search($request);
    }

    public function getAll($request){
        return $this->teamRepository->getAll($request);
    }

    public function store($request){

        $params = [
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ];



        DB::beginTransaction();
        try {
            if(empty($request->id)){
                $params['company_id'] = Auth::user()->company_id;
                $response = $this->teamRepository->create($params);
            }else{

                if($request->field){
                    $params[$request->field] = $request->value;
                }

                $response = $this->teamRepository->updateById($request->id, $params);
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("TeamService@store %s", $e->getMessage()));

            return false;
        }

    }

    public function destroy($request){

        DB::beginTransaction();
        try {
            $this->teamRepository->deleteById($request->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("TeamService@destroy %s", $e->getMessage()));

            return false;
        }

    }


}
