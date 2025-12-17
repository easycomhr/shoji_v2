<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Class BaseRepository.
 */
class UserRepository extends BaseRepository
{

    protected function model()
    {
        return User::class;
    }

    public function search($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "users.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('users.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('users.real_name', 'like', "%$name%");
            })
        ;

        $recordsTotal = $list->count();

        if ($length) {
            $list = $list->skip($start)->take($length);
        }

        $list = $list->get();

        $results = $list ? $list->toArray() : [];

        return [
            'results' =>$results,
            'recordsTotal' =>$recordsTotal,
        ];

    }

    public function searchTerminate($request){
        $company_id = config('constants.COMPANY_ID');

        $start          = $request->start ?? 0;
        $length         = $request->limit ?? config('constant.default_page_size');
        $name           = $request->name ?? null;
        $code           = $request->code ?? null;

        $list = $this->model->where('company_id', $company_id)
            ->select(
                "users.*"
            )
            ->when(!empty($code), function ($query) use ($code) {
                $query->where('users.code', 'like', "%$code%");
            })
            ->when(!empty($name), function ($query) use ($name) {
                $query->where('users.real_name', 'like', "%$name%");
            })
            ->whereNotNull('users.terminate_date')
            ->where('users.terminate_date', '<', now()->toDateString());
        ;

        $recordsTotal = $list->count();

        if ($length) {
            $list = $list->skip($start)->take($length);
        }

        $list = $list->get();

        $results = $list ? $list->toArray() : [];

        return [
            'results' =>$results,
            'recordsTotal' =>$recordsTotal,
        ];

    }

    public function getAll($request){
        $company_id = config('constants.COMPANY_ID') ?? null;
        return $this->model
            ->where('company_id', $company_id)
            ->get();
    }

    public function getByParams($params = []){
        $company_id = config('constants.COMPANY_ID') ?? null;

        $minTerminationDate = !empty($params['min_termination_date'])
            ? Carbon::parse($params['min_termination_date'])->toDateString()
            : null;

        return $this->model
            ->where('company_id', $company_id)
            ->where('is_system_user', 0)
            ->when(!empty($params['user_id']), function ($query) use ($params) {
                $query->where('users.id', $params['user_id']);
            })
            ->when($minTerminationDate, function ($query) use ($minTerminationDate) {
                $query->where(function ($subQuery) use ($minTerminationDate) {
                    $subQuery->whereNull('termination_date')
                        ->orWhere('termination_date', '>=', $minTerminationDate);
                });
            })
            ->get();
    }

    public function getEmployeeInfo($request, $code){
        $company_id = config('constants.COMPANY_ID') ?? null;
        return $this->model
            ->where('company_id', $company_id)
            ->where('code', $code)
            ->first();
    }

}
