<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Services\BaseService;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request){
        $title = "Company Information";
        $company = $this->companyService->findById($request);
        return view('admin.company.index', compact(
            'title',
            'company',
        ));
    }



    public function store(Request $request)
    {


        try {
            // Call UserService to handle the business logic
            $result = $this->companyService->store($request);

            if ($result['success']) {
                return redirect()->back()
                    ->with('success', $result['message'] ?? __('Company information updated successfully.'));
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message'] ?? __('An error occurred while updating employee information.'));
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in storeGeneral: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', __('An unexpected error occurred. Please try again.'));
        }
    }

}
