<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;

/**
 * Class BaseRepository.
 */
class CompanyRepository extends BaseRepository
{

    protected function model()
    {
        return Company::class;
    }

    public function findById($request){
        $id = config('constants.COMPANY_ID');
        return $this->model
            ->where('id', $id)
            ->first();
    }


}
