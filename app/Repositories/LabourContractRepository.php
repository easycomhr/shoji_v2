<?php

namespace App\Repositories;

use App\Models\LabourContract;
use Illuminate\Support\Facades\DB;

class LabourContractRepository extends BaseRepository
{
    protected function model(): string
    {
        return LabourContract::class;
    }

    public function search($request)
    {
        $companyId = config('constants.COMPANY_ID');

        $query = DB::table('labour_contracts')
            ->leftJoin('users', 'users.id', '=', 'labour_contracts.user_id')
            ->leftJoin('contract_types', 'contract_types.id', '=', 'labour_contracts.contract_type_id')
            ->where('labour_contracts.company_id', $companyId)
            ->select([
                'labour_contracts.id',
                'labour_contracts.user_id',
                'users.code',
                'users.name',
                'labour_contracts.contract_type_id',
                'contract_types.name as contract_type_name',
                'labour_contracts.start_date',
                'labour_contracts.end_date',
                'labour_contracts.signed_date',
                'labour_contracts.status',
                'labour_contracts.notes',
            ]);

        if ($request->user_id) {
            $query->where('labour_contracts.user_id', $request->user_id);
        }

        if ($request->name) {
            $query->where('users.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->status) {
            $query->where('labour_contracts.status', $request->status);
        }

        $total = (clone $query)->count();

        $limit = $request->limit ?? 25;
        $start = $request->start ?? 0;

        $data = $query->skip($start)->take($limit)->get();

        return ['data' => $data, 'total' => $total];
    }
}
