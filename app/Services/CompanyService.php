<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    protected CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function findById($request){
        return $this->companyRepository->findById($request);
    }

    public function store($request){

        $params = $request->all();

        DB::beginTransaction();
        try {

            $id = config('constants.COMPANY_ID');
            $company = Company::find($id);
            $company->fill($params);
            $company->save($params);

            DB::commit();

            $message = __('Company information updated successfully.');

            return [
                'success' => true,
                'message' => $message,
                'data' => $company
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(sprintf("CompanyService@store %s", $e->getMessage()));

            return [
                'success' => false,
                'message' => __('An error occurred while processing company information.'),
                'error' => $e->getMessage()
            ];
        }

    }



}
