<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;
use App\Services\BaseService;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    protected CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(){
        $title = "Company Information";

        return view('admin.company.index', compact('title'));
    }


    function store(Request $request){

        $isAllow = BaseService::verifyAction($request, config('constant.actions.update'));

        if(!$isAllow){
            return json_encode([
                'success' => false,
                'message' => __(config('constant.messages.errors.not_enough_permission'))
            ]);
        }
        $response = $this->companyService->store($request);
        if($response){

            return response()->json([
                'success' => true,
                'is_continue' => $request->is_continue == "on" ? 1 : 0,
                'message' => $request->id ? __(config('messages.commons.update_success')) :
                    __(config('messages.commons.create_success')),
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => $request->id ? __(config('messages.commons.update_failed')) :
                __(config('messages.commons.create_failed')),
        ]);
    }

}
