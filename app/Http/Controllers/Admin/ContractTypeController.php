<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContractTypeService;
use Illuminate\Http\Request;

class ContractTypeController extends Controller
{
    protected ContractTypeService $contractTypeService;

    public function __construct(ContractTypeService $contractTypeService)
    {
        $this->contractTypeService = $contractTypeService;
    }

    public function index(){
        $title = "Contract Types";

        return view('admin.contract_type.index', compact('title'));
    }

    public function search(Request $request)
    {
        // verifyAction check if applicable
        $response = $this->contractTypeService->search($request);

        return json_encode([
            "success"   => true,
            "rows"      => $response['results'] ?? [],
            "total"     => $response['recordsTotal'] ?? 0,
        ]);
    }

    function store(Request $request){
        // verifyAction check if applicable

        $response = $this->contractTypeService->store($request);
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

    function destroy(Request $request){
        // verifyAction check if applicable

        $response = $this->contractTypeService->destroy($request);
        if($response){

            return response()->json([
                'success' => true,
                'message' => __(config('messages.commons.delete_success')),
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => __(config('messages.commons.delete_failed')),
        ]);
    }
}