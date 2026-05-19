<?php

namespace App\Repositories;

use App\Models\UserInsurance;
use Illuminate\Support\Facades\DB;

class UserInsuranceRepository extends BaseRepository
{
    protected function model(): string
    {
        return UserInsurance::class;
    }

    public function search($request)
    {
        $companyId = config('constants.COMPANY_ID');

        $query = DB::table('user_insurances')
            ->leftJoin('users', 'users.id', '=', 'user_insurances.user_id')
            ->where('user_insurances.company_id', $companyId)
            ->select([
                'user_insurances.id',
                'user_insurances.user_id',
                'users.code',
                'users.name',
                'users.id_card',
                'users.termination_date',
                'user_insurances.social_insurance_number',
                'user_insurances.social_insurance_start_date',
                'user_insurances.social_insurance_place',
                'user_insurances.health_insurance_number',
                'user_insurances.health_insurance_place',
                'user_insurances.social_insurance_end_date',
                'user_insurances.is_locked',
                DB::raw('TIMESTAMPDIFF(MONTH, social_insurance_start_date, IFNULL(social_insurance_end_date, CURDATE())) as total_months'),
            ]);

        if ($request->user_id) {
            $query->where('user_insurances.user_id', $request->user_id);
        }

        if ($request->name) {
            $query->where('users.name', 'LIKE', '%' . $request->name . '%');
        }

        $total = (clone $query)->count();

        $limit = $request->limit ?? 25;
        $start = $request->start ?? 0;

        $data = $query->skip($start)->take($limit)->get();

        return ['data' => $data, 'total' => $total];
    }
}
